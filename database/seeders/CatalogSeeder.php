<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Catalog::query()->exists()) {
            return;
        }

        Catalog::unguarded(function (): void {
            foreach ($this->categories() as $category) {
                Catalog::create($category);
            }
        });
    }

    /**
     * Build localized default catalog sections for the selected installer locale.
     *
     * @return array<int, array<string, mixed>>
     */
    private function categories(): array
    {
        $data_insert = [
            'en' => [
                [1, 'Auto service', null, null, null],
                [6, 'Internet, IT', 'Internet', '1539095291.png', null],
                [11, 'Computers and phones', null, '1539095439.png', null],
                [13, 'Culture and arts', 'Culture and arts', '1539095633.png', null],
                [19, 'Medicine', null, '1539095914.png', null],
                [27, 'Science and education', 'Science and education', '1539096070.png', null],
                [36, 'Advertising and services', null, '1539096794.png', null],
                [38, 'News, media', 'News, media', '1539096261.png', null],
                [48, 'Shopping centers', null, '1631178614.jpg', null],
                [70, 'Household goods and appliances', 'Household goods and appliances', '1539093561.gif', null],
                [72, 'Recreation and entertainment', null, '1631178724.png', null],
                [82, 'Business world', 'Business world', '1539094605.gif', null],
                [85, 'Home and family', null, '1539094924.png', null],
                [91, 'Animal world', null, '1539095188.png', null],
                [115, 'Food products', 'Food products', '1539096581.png', null],
                [118, 'Transport', null, '1539097109.png', null],
                [122, 'Reference and directories', 'Reference and directories', '1539097377.png', null],
                [2, 'Auto goods', null, null, 1],
                [3, 'Leisure', null, null, 72],
                [4, 'Entertainment', null, null, 72],
                [5, 'Food service', null, null, 115],
                [7, 'Communications', null, null, 6],
                [8, 'Information technology', 'Information technology', null, 6],
                [9, 'Utilities', null, null, 36],
                [10, 'Household services', null, null, 36],
                [12, 'Home appliances', null, null, 70],
                [14, 'Arts', null, null, 13],
                [15, 'Religion', null, null, 13],
                [16, 'Furniture', null, null, 70],
                [17, 'Materials', null, null, 70],
                [18, 'Hardware and fittings', null, null, 70],
                [20, 'Health', null, null, 19],
                [21, 'Beauty', null, null, 19],
                [26, 'Tools', null, null, 70],
                [28, 'Jobs', null, null, 27],
                [29, 'Career', null, null, 27],
                [30, 'Clothing', null, null, 70],
                [31, 'Shoes', null, null, 70],
                [32, 'Security services', null, null, 82],
                [33, 'Security', null, null, 82],
                [35, 'Beverages', null, null, 115],
                [37, 'Printing', null, null, 36],
                [39, 'Sports', null, null, 72],
                [40, 'Recreation', null, null, 72],
                [41, 'Tourism', null, null, 72],
                [42, 'Construction services', null, null, 36],
                [43, 'Finishing materials', null, null, 70],
                [44, 'Construction', null, null, 36],
                [45, 'Real estate', null, null, 82],
                [46, 'Repair', null, null, 36],
                [47, 'Textiles', null, null, 70],
                [49, 'Specialty stores', null, null, 48],
                [50, 'Transport', null, null, 118],
                [51, 'Freight shipping', null, null, 118],
                [52, 'Household goods', null, null, 70],
                [53, 'Electronics', null, null, 11],
                [54, 'Electrical equipment', null, null, 70],
                [55, 'Legal services', null, null, 82],
                [56, 'Financial services', null, null, 82],
                [57, 'Business services', null, null, 82],
                [58, 'Funeral services', null, null, 36],
                [59, 'Office equipment', null, null, 70],
                [60, 'Interior items', null, null, 70],
                [61, 'Stationery', null, null, 70],
                [62, 'Packaging', null, null, 70],
                [63, 'Pet products', 'Pet products', null, 91],
                [64, 'City', null, null, 122],
                [65, 'Government', null, null, 38],
                [66, 'Veterinary', null, null, 91],
                [67, 'Emergency', null, null, 122],
                [68, 'Reference', null, null, 122],
                [69, 'Emergency services', null, null, 122],
                [73, 'Children and school supplies', null, null, 70],
                [75, 'Stationery and stamps', 'Stationery and stamps', null, 70],
                [76, 'Climate control equipment', 'Climate control equipment', null, 70],
                [78, 'Gifts and jewelry', 'Gifts and jewelry', null, 70],
                [80, 'Textiles and haberdashery', 'Textiles and haberdashery', null, 70],
                [81, 'Household goods', 'Household goods', null, 70],
                [83, 'Banks', 'Banks', null, 82],
                [84, 'Business news', 'Business news', null, 82],
                [86, 'Women portals', 'Women portals', null, 85],
                [87, 'Family circle', 'Family circle', null, 85],
                [88, 'Your home', null, null, 85],
                [89, 'DIY crafts', null, null, 85],
                [90, 'Homestead', 'Homestead', null, 85],
                [92, 'Animal world', 'Animal world', null, 91],
                [93, 'Web studios and SEO companies', 'Web studios and SEO companies', null, 6],
                [94, 'Accessories and peripherals', 'Accessories and peripherals', null, 11],
                [95, 'Marketplaces and hardware', 'Marketplaces and hardware', null, 11],
                [96, 'Stores', 'Stores', null, 11],
                [97, 'Software', 'Software', null, 11],
                [98, 'Networks and communications', 'Networks and communications', null, 11],
                [99, 'Technical information and forums', 'Technical information and forums', null, 11],
                [100, 'Events, news and information', 'Events, news and information', null, 13],
                [101, 'Film and video', 'Film and video', null, 13],
                [102, 'Literature', 'Literature', null, 13],
                [103, 'Music', 'Music', null, 13],
                [104, 'Design', 'Design', null, 13],
                [105, 'Architecture', 'Architecture', null, 13],
                [106, 'Museums and galleries', null, null, 13],
                [107, 'Organizations and institutions', 'Organizations and institutions', null, 19],
                [108, 'Home doctor', 'Home doctor', null, 19],
                [109, 'Universities', 'Universities', null, 27],
                [110, 'Schools and gymnasiums', 'Schools and gymnasiums', null, 27],
                [111, 'Training courses and video lessons', 'Training courses and video lessons', null, 27],
                [112, 'Scientific organizations', 'Scientific organizations', null, 27],
                [113, 'Magazines and newspapers', 'Magazines and newspapers', null, 38],
                [114, 'Television, radio and cinema', null, null, 38],
                [117, 'Printing and typography', null, null, 36],
                [120, 'Parts and goods', 'Parts and goods', null, 118],
                [121, 'Trucks and special equipment', 'Trucks and special equipment', null, 118],
                [123, 'Auto service', null, null, 118],
                [124, 'Auto goods', null, null, 118],
                [125, 'Emergency', null, null, 122],
                [149, 'Beauty', null, null, 20],
                [152, 'Chemicals', null, null, 70],
                [162, 'Media', null, null, 38],
            ],
            'ru' => [
                [1, 'Автосервис', null, null, null],
                [6, 'Интернет, IT', 'Интернет', '1539095291.png', null],
                [11, 'Компьютеры и телефоны', null, '1539095439.png', null],
                [13, 'Культура и искусство', 'Культура и искусство', '1539095633.png', null],
                [19, 'Медицина', null, '1539095914.png', null],
                [27, 'Наука и образование', 'Наука и образование', '1539096070.png', null],
                [36, 'Реклама и услуги', null, '1539096794.png', null],
                [38, 'Новости, СМИ', 'Новости, СМИ', '1539096261.png', null],
                [48, 'Торговые комплексы', null, '1631178614.jpg', null],
                [70, 'Бытовые товары и техника', 'Бытовые товары и техника', '1539093561.gif', null],
                [72, 'Отдых и развлечения', null, '1631178724.png', null],
                [82, 'Деловой мир', 'Деловой мир', '1539094605.gif', null],
                [85, 'Дом и семья', null, '1539094924.png', null],
                [91, 'Животный мир', null, '1539095188.png', null],
                [115, 'Продукты питания', 'Продукты питания', '1539096581.png', null],
                [118, 'Транспорт', null, '1539097109.png', null],
                [122, 'Справки и каталоги', 'Справки и каталоги', '1539097377.png', null],
                [2, 'Автотовары', null, null, 1],
                [3, 'Досуг', null, null, 72],
                [4, 'Развлечения', null, null, 72],
                [5, 'Общественное питание', null, null, 115],
                [7, 'Связь', null, null, 6],
                [8, 'Информационные технологии', 'Информационные технологии', null, 6],
                [9, 'Коммунальные', null, null, 36],
                [10, 'бытовые', null, null, 36],
                [12, 'Бытовая техника', null, null, 70],
                [14, 'Искусство', null, null, 13],
                [15, 'Религия', null, null, 13],
                [16, 'Мебель', null, null, 70],
                [17, 'Материалы', null, null, 70],
                [18, 'Фурнитура', null, null, 70],
                [20, 'Здоровье', null, null, 19],
                [21, 'Красота', null, null, 19],
                [26, 'Инструмент', null, null, 70],
                [28, 'Работа', null, null, 27],
                [29, 'Карьера', null, null, 27],
                [30, 'Одежда', null, null, 70],
                [31, 'Обувь', null, null, 70],
                [32, 'Охрана', null, null, 82],
                [33, 'Безопасность', null, null, 82],
                [35, 'Напитки', null, null, 115],
                [37, 'Полиграфия', null, null, 36],
                [39, 'Спорт', null, null, 72],
                [40, 'Отдых', null, null, 72],
                [41, 'Туризм', null, null, 72],
                [42, 'Строительные', null, null, 36],
                [43, 'отделочные материалы', null, null, 70],
                [44, 'Строительство', null, null, 36],
                [45, 'Недвижимость', null, null, 82],
                [46, 'Ремонт', null, null, 36],
                [47, 'Текстиль', null, null, 70],
                [49, 'Спецмагазины', null, null, 48],
                [50, 'Транспорт', null, null, 118],
                [51, 'Грузоперевозки', null, null, 118],
                [52, 'Хозтовары', null, null, 70],
                [53, 'Электроника', null, null, 11],
                [54, 'Электротехника', null, null, 70],
                [55, 'Юридические', null, null, 82],
                [56, 'финансовые', null, null, 82],
                [57, 'бизнес-услуги', null, null, 82],
                [58, 'ритуальные услуги', null, null, 36],
                [59, 'Офисная техника', null, null, 70],
                [60, 'Предметы интерьера', null, null, 70],
                [61, 'Канцелярия', null, null, 70],
                [62, 'Упаковка', null, null, 70],
                [63, 'Товары для животных', 'Товары для животных', null, 91],
                [64, 'Город', null, null, 122],
                [65, 'Власть', null, null, 38],
                [66, 'Ветеринария', null, null, 91],
                [67, 'Аварийные', null, null, 122],
                [68, 'справочные', null, null, 122],
                [69, 'экстренные службы', null, null, 122],
                [73, 'Детские и школьные', null, null, 70],
                [75, 'Канцелярские товары, печати', 'Канцелярские товары, печати', null, 70],
                [76, 'Климатическая техника', 'Климатическая техника', null, 70],
                [78, 'Подарки, украшения', 'Подарки, украшения', null, 70],
                [80, 'Текстиль, галантерея', 'Текстиль, галантерея', null, 70],
                [81, 'Хозяйственно-бытовые товары', 'Хозяйственно-бытовые товары', null, 70],
                [83, 'Банки', 'Банки', null, 82],
                [84, 'Деловые новости', 'Деловые новости', null, 82],
                [86, 'Женские порталы', 'Женские порталы', null, 85],
                [87, 'Семейный круг', 'Семейный круг', null, 85],
                [88, 'Твоё жильё', null, null, 85],
                [89, 'Умелые руки', null, null, 85],
                [90, 'Усадьба', 'Усадьба', null, 85],
                [92, 'В мире животных', 'В мире животных', null, 91],
                [93, 'Веб-студии, SEO-компании', 'Веб-студии, SEO-компании', null, 6],
                [94, 'Аксессуары и периферия', 'Аксессуары и периферия', null, 11],
                [95, 'Барахолки, железо', 'Барахолки, железо', null, 11],
                [96, 'Магазины', 'Магазины', null, 11],
                [97, 'Программное обеспечение', 'Программное обеспечение', null, 11],
                [98, 'Сети и связь', 'Сети и связь', null, 11],
                [99, 'Техническая информация, форумы', 'Техническая информация, форумы', null, 11],
                [100, 'Афиши, новости, информация', 'Афиши, новости, информация', null, 13],
                [101, 'Кино и видео', 'Кино и видео', null, 13],
                [102, 'Литература', 'Литература', null, 13],
                [103, 'Музыка', 'Музыка', null, 13],
                [104, 'Дизайн', 'Дизайн', null, 13],
                [105, 'Архитектура', 'Архитектура', null, 13],
                [106, 'Музеи и галереи', null, null, 13],
                [107, 'Организации и учреждения', 'Организации и учреждения', null, 19],
                [108, 'Домашний доктор', 'Домашний доктор', null, 19],
                [109, 'ВУЗы', 'ВУЗы', null, 27],
                [110, 'Школы, гимназии', 'Школы, гимназии', null, 27],
                [111, 'Учебные курсы, видеоуроки', 'Учебные курсы, видеоуроки', null, 27],
                [112, 'Научные организации', 'Научные организации', null, 27],
                [113, 'Журналы и газеты', 'Журналы и газеты', null, 38],
                [114, 'Телевидение, радио, кино', null, null, 38],
                [117, 'Типография и полиграфия', null, null, 36],
                [120, 'Запчасти и товары', 'Запчасти и товары', null, 118],
                [121, 'Грузовая и спецтехника', 'Грузовая и спецтехника', null, 118],
                [123, 'Автосервис', null, null, 118],
                [124, 'Автотовары', null, null, 118],
                [125, 'Аварийные', null, null, 122],
                [149, 'Красота', null, null, 20],
                [152, 'Химия', null, null, 70],
                [162, 'СМИ', null, null, 38],
            ],
        ];

        return array_map(
            fn (array $category) => $this->category(...$category),
            $data_insert[$this->locale()] ?? $data_insert['en'],
        );
    }

    /**
     * Resolve the locale used for installer seed data.
     */
    private function locale(): string
    {
        return in_array(config('app.locale'), config('app.locales', []), true)
            ? config('app.locale')
            : config('app.fallback_locale', 'en');
    }

    /**
     * Build one catalog row from compact seed data.
     */
    private function category(int $id, string $name, ?string $keywords, ?string $image, ?int $parentId): array
    {
        return [
            'id' => $id,
            'name' => $name,
            'description' => null,
            'keywords' => $keywords,
            'image' => $image,
            'parent_id' => $parentId,
        ];
    }
}
