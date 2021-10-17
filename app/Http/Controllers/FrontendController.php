<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\{SettingsHelpers, StringHelper};
use App\Models\{Catalog, Links, Feedback};
use App\Events\{FeedbackMailEvent, NewlinkNotifyEvent};
use URL;
use stdClass;

class FrontendController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Главная страница';
        $description = 'Белый каталог сайтов';
        $keywords = 'белый каталог сайтов, добавить сайт';

        $catalogs = Catalog::selectRaw('catalog.name,catalog.id,catalog.image,COUNT(links.status) AS number_links')
            ->leftJoin('links', 'links.catalog_id', '=', 'catalog.id')
            ->where('catalog.parent_id', 0)
            ->groupBy('catalog.id')
            ->groupBy('catalog.name')
            ->groupBy('catalog.image')
            ->orderBy('catalog.name')
            ->get();

        $arr = [];
        $arrayCatalog = [];
        foreach ($catalogs as $row) {
            $arrayCatalog[] = [$row->name, $row->id, $row->image, $row->number_links];
        }

        $arrayCatalog[] = ['Разное', 0, null, 0];

        $total = count($arrayCatalog);
        $number = (int)($total / SettingsHelpers::getSetting('COLUMNS_NUMBER'));

        if ((float)($total / SettingsHelpers::getSetting('COLUMNS_NUMBER')) - $number != 0) $number++;

        for ($i = 0; $i < $number; $i++) {
            for ($j = 0; $j < SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++) {
                if (isset($arrayCatalog[$j * $number + $i])) $arr[$i][$j] = $arrayCatalog[$j * $number + $i];
            }
        }

        $links = Links::orderBy('id', 'DESC')->where('status', 1)->take(5)->get();
        $rank = 1;

        return view('frontend.index', compact('arr', 'number', 'links', 'rank', 'description', 'keywords'))->with('title', $title);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function catalog($id = 0)
    {
        $title = 'Разное';
        $description = 'Белый каталог сайтов';
        $keywords = 'белый каталог сайтов, добавить сайт';
        $catalog_name = 'Разное';
        $paginator = null;
        $arr = [];
        $number = 0;

        if ($id > 0) {

            $topbar = [];

            $catalogs = Catalog::selectRaw('catalog.name,catalog.id,catalog.image,COUNT(links.status) AS number_links')
                ->leftJoin('links', 'links.catalog_id', '=', 'catalog.id')
                ->where('catalog.parent_id', $id)
                ->groupBy('catalog.id')
                ->groupBy('catalog.name')
                ->groupBy('catalog.image')
                ->orderBy('catalog.name')
                ->get();

            $arrayCatalog = [];
            $catalogIds = [];

            foreach ($catalogs as $row) {
                $arrayCatalog[] = [$row->name, $row->id, $row->image, $row->number_links];
                $catalogIds[] = $row->id;
            }

            $total = count($arrayCatalog);
            $number = (int)($total / SettingsHelpers::getSetting('COLUMNS_NUMBER'));

            if ((float)($total / SettingsHelpers::getSetting('COLUMNS_NUMBER')) - $number != 0) $number++;

            for ($i = 0; $i < $number; $i++) {
                for ($j = 0; $j < SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++) {
                    if (isset($arrayCatalog[$j * $number + $i])) $arr[$i][$j] = $arrayCatalog[$j * $number + $i];
                }
            }

            $arrayPathWay = Catalog::topbarMenu($topbar, $id);
            $pathway = '<a href="' . URL::route('index') . '">Главная</a>';

            for ($i = 0; $i < count($arrayPathWay); $i++) {
                if ($arrayPathWay[$i][0] == $id) {
                    $pathway .= '» ' . $arrayPathWay[$i][1];
                } else {
                    $pathway .= '» <a href="' . URL::route('catalog', ['id' => $arrayPathWay[$i][0]]) . '">' .$arrayPathWay[$i][1] . '</a>';
                }
            }

            $catalog = Catalog::find($id);

            if (!$catalog)  return redirect()->away(URL::route('index'));;

            if ($catalog->parent_id == 0) {
                $links = Links::where('status', 1)->whereIn('catalog_id', $catalogIds)->orderBy('id', 'DESC')->take(5)->get();
                $rank = 1;
            } else {
                $links = Links::where('catalog_id', $id)->where('status', 1)->paginate(10);
                $rank = $links->firstItem();
                $paginator = $links->links();
            }

            $catalog_name = $catalog->name;
            $title = $catalog->name;
            $description = $catalog->description ?? $description;
            $keywords = $catalog->keywords ?? $keywords;
        } else {
            $pathway = '<a href="' . URL::route('index') . '">Главная</a>» Разное';
            $links = Links::where('catalog_id', $id)->where('status', 1)->paginate(10);
            $rank = $links->firstItem();
            $paginator = $links->links();
        }

        return view('frontend.index', compact('links', 'arr', 'number', 'paginator', 'pathway', 'rank', 'catalog_name', 'description', 'keywords'))->with('title', $title);

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($id)
    {

        $link = Links::where('id', $id)->where('status', 1)->first();

        if (!$link) return redirect()->away(URL::route('index'));;

        Links::where('id', $id)->update(['views' => $link->views + 1]);

        return view('frontend.info', compact('link'))->with('title', $link->name);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addurl()
    {

        $options = [0 => '-Разное'];
        $options = Catalog::ShowTree($options, 0);

        return view('frontend.addurl', compact('options'))->with('title', 'Добавить сайт');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $rules = [
            'name' => 'required',
            'url' => 'required|url|unique:links',
            'description' => 'required|min:100|max:300',
            'full_description' => 'required|min:200|max:2000',
            'email' => 'email',
            'catalog_id' => 'required|integer',
            'captcha' => 'required|captcha',
            'agree' => 'required',
            'htmlcode_banner' => 'nullable|regex:/^<a([^>]*)\s+href=(")?http(s)?:\/\/[^>]*>\s*<\s*img[^>]*\s+src=(")?http(s)?:\/\/[^>]*><\/a>$/si',
        ];

        $message = [
            'name.required' => 'Не указано название!',
            'url.required' => 'Не указан URL адрес сайта!',
            'url.url' => 'Не верно указан URL адрес сайта!',
            'url.unique' => 'Этот сайт уже есть в каталоге!',
            'email.email' => 'Не верно указан email!',
            'description.required' => 'Не указано описание!',
            'description.min' => 'Количество символов в описание не должно быть меньше :min',
            'description.max' => 'Количество символов в описание не должно быть больше :max',
            'full_description.required' => 'Не указано полное описание!',
            'full_description.min' => 'Количество символов в полном описание не должно быть меньше :min',
            'full_description.max' => 'Количество символов в полном описание не должно быть больше :max',
            'catalog_id.required' => 'Выберите раздел!',
            'captcha.required' => 'Не указан защитный код!',
            'agree.required' => 'Вы должны принять правила каталога',
            'htmlcode_banner.regex' => 'HTML код баннера введен неверно!',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $link = Links::create(array_merge($request->all(), [
            'description' => StringHelper::removeHtmlTags($request->input('description')),
            'full_description' => StringHelper::removeHtmlTags($request->input('full_description')),
            'status' => SettingsHelpers::getSetting('ADD_LINKS_WITHOUT_CHECK') == 1 ? 1 : 0
        ]));

        event(new NewlinkNotifyEvent($link));

        $msg = SettingsHelpers::getSetting('ADD_LINKS_WITHOUT_CHECK') == 1 ? 'Сайт добавлен в каталог' : 'Сайт добавлен в каталог и после проверки будет доступен в каталоге';

        return redirect(URL::route('addurl'))->with('success', $msg);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirect($id)
    {

        $link = Links::where('id', $id)->first();

        if (!$link) return redirect()->away(URL::route('index'));

        if (substr($link->url, 0, 7) == "http://" or substr($link->url, 0, 8) == "https://")
            $redirect = $link->url;
        else
            $redirect = 'http://' . $link->url;

        return redirect()->away($redirect);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rules()
    {
        return view('frontend.rules')->with('title', 'Правила каталога сайтов');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('frontend.contact')->with('title', 'Обратная связь');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMsg(Request $request)
    {
        $rules = [
            'message' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'captcha' => 'required|captcha',
        ];

        $message = [
            'name.required' => 'Укажите Ваше имя!',
            'email.required' => 'Не указан Email!',
            'email.email' => 'Не верно указан Email!',
            'message.required' => 'Введите сообщение',
            'catalog_id.required' => 'Выберите раздел!',
            'captcha.required' => 'Не указан защитный код!',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Feedback::create(array_merge($request->all(), ['ip' => $request->ip()]));

        $data = new stdClass();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->message = $request->message;

        event(new FeedbackMailEvent($data));


        return redirect(URL::route('contact'))->with('success', 'Ваше сообщение успешно отправлено');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function sitemap()
    {
        $total_links = Links::where('status', 1)->count();
        $l = intval(($total_links - 1) / Links::PER_PAGE) + 1;

        $total_categories = Catalog::count();
        $c = intval(($total_categories - 1) / Catalog::PER_PAGE) + 1;

        return response()->view('frontend.sitemap', compact('l', 'c'))->header('Content-type', 'text/xml');
    }

    /**
     * @param int $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maplinks($page = 1)
    {
        $limit = Links::PER_PAGE;
        $offset = Links::PER_PAGE * ($page - 1);
        $links = Links::where('status', 1)->limit($limit)->offset($offset)->get();

        return response()->view('frontend.maplinks', compact('links'))->header('Content-type', 'text/xml');
    }

    /**
     * @param int $page
     * @return \Illuminate\Http\Response
     */
    public function mapcatalogs($page = 1)
    {
        $limit = Catalog::PER_PAGE;
        $offset = Catalog::PER_PAGE * ($page - 1);
        $catalogs = Catalog::limit($limit)->offset($offset)->get();

        return response()->view('frontend.mapcatalogs', compact('catalogs'))->header('Content-type', 'text/xml');
    }
}
