import re
import copy

from collections import OrderedDict
from .scraping_base import ScrapingBaseClass




class CreateCityCsvFile(ScrapingBaseClass):

    def __init__(self, csv_name):
        self.column_names = ['物件名', '路線', '最寄駅', '距離', '住所', '築年数', '賃料', '部屋数', 'プラン', '面積','管理費', '敷金', '礼金']
        super().__init__(csv_name)

    # 街の情報を取得
    def get_town_data(self, url):

        soup = self.get_html(url)

        # 総ページ数の取得
        # total_pages = town_data.get_total_page(soup, 'pagination-parts', 'a')
        total_pages = 2

        # URLにページが表記を追加する
        format_url = self.append_page_format(url)

        # データを追加するためのリスト
        scraped_data_list = [[],[],[],[],[],[],[],[],[],[],[],[],[]]

        # ページごとにHTMLを取得
        for i in range(total_pages):

            # 現在のページを出力する
            print(f'{i+1}/{total_pages}')

            if not i is 0:
                soup = self.get_html(format_url)

            # 物件を全て取得
            building_html_elements_list = soup.find_all(class_="cassetteitem")

            if not building_html_elements_list:
                return False

            # 物件ごとに要素を取得する　
            for building_html_elements in building_html_elements_list:

                # 物件概要のリストを作成：物件名、路線、駅名、徒歩距離、住所、築年数
                name = self.get_names(building_html_elements)
                route, station, distance = self.get_statuses(building_html_elements)
                address = self.get_address(building_html_elements)
                building = self.get_building(building_html_elements)

                # 詳細を取得：家賃、部屋タイプ、部屋数、占有面積、管理費、敷金、礼金
                prices = self.get_price(building_html_elements)
                values, plans = self.get_madori(building_html_elements)
                areas = self.get_menseki(building_html_elements)
                maintenance_fees = self.get_administration(building_html_elements)
                deposits = self.get_deposit(building_html_elements)
                gratuities = self.get_gratuity(building_html_elements)

                # 部屋詳細の件数で回す
                for m in range(len(prices)):
                    scraped_data_list[0].extend(copy.copy(name))
                    scraped_data_list[1].extend(copy.copy(route))
                    scraped_data_list[2].extend(copy.copy(station))
                    scraped_data_list[3].extend(copy.copy(distance))
                    scraped_data_list[4].extend(copy.copy(address))
                    scraped_data_list[5].extend(copy.copy(building))
                    scraped_data_list[6].extend([copy.copy(prices[m])])
                    scraped_data_list[7].extend([copy.copy(values[m])])
                    scraped_data_list[8].extend([copy.copy(plans[m])])
                    scraped_data_list[9].extend([copy.copy(areas[m])])
                    scraped_data_list[10].extend([copy.copy(maintenance_fees[m])])
                    scraped_data_list[11].extend([copy.copy(deposits[m])])
                    scraped_data_list[12].extend([copy.copy(gratuities[m])])

            format_url = self.increment_current_page(format_url, i+1)

        # データの転置
        self.transposed_data(self.column_names, scraped_data_list)
        plan_list = list(OrderedDict.fromkeys(scraped_data_list[8]))
        return plan_list

    # 建物名の取得
    def get_names(self, soup):
        return self.get_property_details(soup, "cassetteitem_content-title")

    # 住所
    def get_address(self, soup):
        return self.get_property_details(soup, "cassetteitem_detail-col1")

    # 賃料
    def get_price(self, soup):
        return self.get_property_details(soup, "cassetteitem_other-emphasis", end_target_string='万円')

    # 管理費
    def get_administration(self, soup):
        return self.get_property_details(soup, "cassetteitem_price--administration", end_target_string='円')

    # 敷金
    def get_deposit(self, soup):
        return self.get_property_details(soup, "cassetteitem_price--deposit", end_target_string='万円')

    # 礼金
    def get_gratuity(self, soup):
        return self.get_property_details(soup, "cassetteitem_price--gratuity", end_target_string='万円')

    # 間取りを部屋数と部屋タイプで分ける
    def get_madori(self, soup):

        madori_elements = self.create_list(soup.find_all(class_="cassetteitem_madori"))

        values = []
        plans = []

        for i in madori_elements:
            # ワンルームがカタカナ表記の場合、1Rに変換する
            if i == 'ワンルーム':
                values.append(1)
                plans.append('R')
            else:
                values.append(re.search(r'\d+', i).group())
                plans.append(re.search(r'\D+', i).group())
        return values, plans

    # 占有面積
    def get_menseki(self, soup):
        menseki = soup.find_all(class_="cassetteitem_menseki")
        return self.create_list_split(menseki, 'm', 1, 0)

    # 築年数
    def get_building(self, soup):
        building = self.create_list_split(soup.find_all(class_="cassetteitem_detail-col3"), '\n', 2, 1)
        building[0] = '築0年' if building[0] == '新築' else building[0]
        return self.create_list_remove_string(building, '築', '年')

    # 最寄駅と駅からの距離を取得
    def get_statuses(self, soup):

        route = []
        station = []
        distance = []
        statuses = soup.find_all(class_="cassetteitem_detail-col2")

        # バスを検索の条件から削除
        status = self.delete_target_string(self.create_list_split(statuses, '\n'), ['バス', r'車(\d+)'])

        for i in status:
            # 「分」の入ったデータを取得
            values = [(n, re.search(r'(\d+)分', n)) for n in i]
            filtered_values = [(n, int(match.group(1))) for n, match in values if match]

            if filtered_values:

                # 最寄駅の取得
                near_station = min(filtered_values, key=lambda x: x[1])
                station_name = near_station[0][near_station[0].find('/')+1:near_station[0].find(' 歩')]

                # 駅名かどうかを判断する
                route.append(near_station[0][0:near_station[0].find('/')])
                station.append(station_name)

                if len(near_station) > 1:
                    distance.append(near_station[1])
                else:
                    distance.append(None)
            else:
                station.append(None)
                distance.append(None)
        return route, station, distance

    # 該当文字が存在する要素そのものを削除
    def delete_target_string(self, data, target):
        filtered_data = []

        for i in data:

            delete_data = []
            delete = False

            for item in i:

                for n in target:
                    if re.search(n, item, flags=0):
                        delete = True

                if not delete:
                    delete_data.append(item)
                else:
                    delete_data.append('')

            filtered_data.append(delete_data)

        return filtered_data
