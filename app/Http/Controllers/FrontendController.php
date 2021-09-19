<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\{SettingsHelpers, StringHelper};
use App\Models\{Catalog, Links, Feedback};
use URL;

class FrontendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $title = 'Главная страница';
        $description = '';
        $keywords = '';

        $catalogs = Catalog::selectRaw('catalog.name,catalog.id,catalog.image,COUNT(links.status) AS number_links')
            ->leftJoin('links', 'links.catalog_id', '=', 'catalog.id')
            ->where('catalog.parent_id', $id)
            ->groupBy('catalog.id')
            ->groupBy('catalog.name')
            ->groupBy('catalog.image')
            ->orderBy('catalog.name')
            ->get();

        $arraycat = [];

        foreach ($catalogs as $catalog) {
            $arraycat[] = array($catalog->name, $catalog->id, $catalog->image, $catalog->number_links);
        }

        $total = count($arraycat);
        $number = (int)($total / SettingsHelpers::getSetting('COLUMNS_NUMBER'));

        if ((float)($total / SettingsHelpers::getSetting('COLUMNS_NUMBER')) - $number != 0) $number++;

        $arr = [];

        // Form an array
        for ($i = 0; $i < $number; $i++) {
            for ($j = 0; $j < SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++) {
                if (isset($arraycat[$j * $number + $i])) $arr[$i][$j] = $arraycat[$j * $number + $i];
            }
        }

        if ($id) {
            $links = Links::where('catalog_id', $id)->where('status', 1)->paginate(10);
            $rank = $links->firstItem();
        } else {
            $links = Links::orderBy('id', 'DESC')->where('status', 1)->take(5)->get();
            $rank = 1;
        }

        $pathway = '';
        $catalog_name = '';

        if ($id > 0) {
            $topbar = [];
            $arraypathway = Catalog::topbarMenu($topbar, $id);
            $pathway = '<a href="' . URL::route('index') . '">Главная</a> ';

            for ($i = 0; $i < count($arraypathway); $i++) {
                if ($arraypathway[$i][0] == $id) {
                    $pathway .= '» ' . $arraypathway[$i][1];
                } else {
                    $pathway .= '» <a href="' . URL::route('index', ['id' => $arraypathway[$i][0]]) . '">' . $arraypathway[$i][1] . '</a>';
                }
            }

            $catalogRow = Catalog::select('name')->where('id', $id)->first();
            $catalog_name = $catalogRow->name;

            $title = $catalogRow->name;
            $description = $catalogRow->description ? $catalogRow->description : $description;
            $keywords = $catalogRow->keywords ? $catalogRow->keywords : $keywords;
        }

        return view('frontend.index', compact('arr', 'number', 'links', 'id', 'pathway', 'rank', 'catalog_name', 'description', 'keywords'))->with('title', $title);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($id)
    {

        $link = Links::where('id', $id)->where('status', 1)->first();

        if (!$link) abort(404);

        Links::where('id', $id)->update(['views' => $link->views + 1]);

        return view('frontend.info', compact('link'))->with('title', $link->name);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addurl()
    {
        $options = [];
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
            'catalog_id' => 'required|integer',
            'captcha' => 'required|captcha',
            'agree' => 'required'
        ];

        $message = [
            'name.required' => 'Не указано название!',
            'url.required' => 'Не указан URL адрес сайта!',
            'url.url' => 'Не верно указан URL адрес сайта!',
            'url.unique' => 'Этот сайт уже есть в каталоге!',
            'email.required' => 'Не указан Email!',
            'description.required' => 'Не указано описание!',
            'description.min' => 'Количество символов в описание не должно быть меньше :min',
            'description.max' => 'Количество символов в описание не должно быть больше :max',
            'full_description.required' => 'Не указано полное описание!',
            'full_description.min' => 'Количество символов в полном описание не должно быть меньше :min',
            'full_description.max' => 'Количество символов в полном описание не должно быть больше :max',
            'catalog_id.required' => 'Выберите раздел!',
            'captcha.required' => 'Не указан защитный код!',
            'agree.required' => 'Вы должны принять правила каталога',

        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Links::create(array_merge($request->all(), ['description' => StringHelper::removeHtmlTags($request->input('description')), 'full_description' => StringHelper::removeHtmlTags($request->input('full_description')), 'status' => 1]));

        return redirect(URL::route('addurl'))->with('success', 'Сайт добавлен в каталог');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirect($id)
    {

        $link = Links::where('id', $id)->first();

        if (!$link) abort(404);

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
        $message['email'] = $request->email;
        $message['name'] = $request->name;
        $message['msg'] = $request->message;

        Feedback::create(array_merge($request->all(), ['ip' => $request->getClientIp()]));

        /**
        Mail::send('emails.feedback', ['email' => $message['email'], 'name' => $message['name'], 'msg' => $message['msg']], function ($message) {
            $message->from('no-reply@' . $_SERVER['SERVER_NAME'], getSetting('SITE_NAME'));
            $message->to(getSetting('EMAIL'), getSetting('SITE_NAME'))->subject('Cообщение с сайта ' . $_SERVER['SERVER_NAME']);
        });
         * */

        return redirect(URL::route('contact'))->with('success', 'Спасибо за Ваше сообщение! Вы получите ответ как можно скоро. ');
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
