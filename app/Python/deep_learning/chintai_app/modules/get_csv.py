import time

import requests
import pandas as pd
import re
import itertools

from bs4 import BeautifulSoup
from collections import OrderedDict


class GetData:

    def __init__(self, csv, url, data):
        self.data = data
        self.soup = None
        self.url = url
        self.csv_name = csv

    # htmlの取得
    def get_html(self):
        time.sleep(5)
        load_url = self.url
        html = requests.get(load_url)
        self.soup = BeautifulSoup(html.content, "html.parser")
        return

    # 総ページ数の取得
    def get_page(self):
        pages = self.soup.find(class_="pagination-parts").find_all('a')
        last_page = int(pages[-1].text) if pages else None
        print('last_page', last_page)
        return last_page

    # ページ数の確認
    def search_page(self):
        if "page=" not in self.url:
            self.url = self.url + "&page=1"
        return

    # ページ数を次のページに取り替える
    def replace_page(self, current_page):
        self.url = self.url.replace(f"page={current_page}", f"page={current_page+1}")
        return

    # htmlタグからテキストのみ取り出す
    def cre_list(self, info_tab):
        return [i.text for i in info_tab]

    # 文字列から複数のリストを作成する
    def cre_list_split(self, info_tab, st, num_str=None, num_list=None):

        # 区切り文字を指定してリストに返す
        if num_list is None and num_str is None:
            return [i.text.split(f'{st}') for i in info_tab]
        # 区切り文字と最大分割回数を指定してリストに返す
        else:
            return [i.text.split(f'{st}', num_str)[num_list] for i in info_tab]

    #  リストないの文字列から指定文字を切り取る
    def cre_list_remove_string(self, info_tab, start_str=None, end_str=None):
        if not start_str:
            return [i[0:i.find(end_str)] for i in info_tab]
        elif not end_str:
            return [i[i.find(start_str) + 1:] for i in info_tab]
        else:
            return [i[i.find(start_str) + 1:i.find(end_str)] for i in info_tab]


    # 項目ごとに取得したリストを整理してCSV化する
    def transposed_data(self, column_name):
        transposed_data = list(zip(*self.data))
        df = pd.DataFrame(transposed_data, columns=column_name)
        df.to_csv(self.csv_name, index=False)
        return


class GetDtail(GetData):

    def __init__(self, url, csv_name):
        self.data = []
        self.url = url
        self.column_names = ['物件名', '路線', '最寄駅', '距離', '住所', '築年数', '賃料', '部屋数', 'プラン', '面積','管理費', '敷金', '礼金']
        self.counter = 0
        
        super().__init__(csv_name, self.url, self.data)

    # 街の情報を取得
    def get_town_data(self, pages):
        self.search_page()

        # ページごとにHTMLを取得
        for i in range(pages):
            print(f'{i+1}/{pages}')
            self.get_html()

            # 物件ごとに取得
            chintai_names = self.soup.find_all(class_="cassetteitem")
            if not chintai_names:
                return False

            for n in chintai_names:

                items = []
                details = []

                statuses = self.get_statuses(n)
                madori = self.get_madori(n)

                # 項目を取得
                items.append(self.get_names(n))
                items.append(statuses[0])
                items.append(statuses[1])
                items.append(statuses[2])
                items.append(self.get_address(n))
                items.append(self.get_building(n))

                # 詳細を取得
                details.append(self.get_price(n))
                details.append(madori[0])
                details.append(madori[1])
                details.append(self.get_menseki(n))
                details.append(self.get_administration(n))
                details.append(self.get_deposit(n))
                details.append(self.get_gratuity(n))
                # details.append(self.get_floor(n))

                for m in range(len(details[0])):
                    combined_details = []

                    # 詳細を物件ごとに整理
                    for d in details:
                        combined_details.append([d[m]])

                    if self.counter == 0:
                        self.data.append(items[0].copy())
                        self.data.append(items[1].copy())
                        self.data.append(items[2].copy())
                        self.data.append(items[3].copy())
                        self.data.append(items[4].copy())
                        self.data.append(items[5].copy())
                        self.data.append(combined_details[0].copy())
                        self.data.append(combined_details[1].copy())
                        self.data.append(combined_details[2].copy())
                        self.data.append(combined_details[3].copy())
                        self.data.append(combined_details[4].copy())
                        self.data.append(combined_details[5].copy())
                        self.data.append(combined_details[6].copy())
                        # self.data.append(combined_details[7].copy())
                        self.counter = 1

                    else:
                        self.data[0].extend(items[0])
                        self.data[1].extend(items[1])
                        self.data[2].extend(items[2])
                        self.data[3].extend(items[3])
                        self.data[4].extend(items[4])
                        self.data[5].extend(items[5])
                        self.data[6].extend(combined_details[0])
                        self.data[7].extend(combined_details[1])
                        self.data[8].extend(combined_details[2])
                        self.data[9].extend(combined_details[3])
                        self.data[10].extend(combined_details[4])
                        self.data[11].extend(combined_details[5])
                        self.data[12].extend(combined_details[6])
                        # self.data[13].extend(combined_details[7])

            self.replace_page(i+1)

        # データの転置
        self.transposed_data(self.column_names)
        plan_list = list(OrderedDict.fromkeys(self.data[8]))
        return plan_list

    # 項目と詳細を結合する
    # def merge_items(self):


    # 階数の取得
    def get_floor(self, soup):
        # t = self.soup.text.replace('\n', '').replace('\t', '')
        a = soup.find_all('td', text=re.compile(r"\d+階"))
        text_list = [td.get_text(strip=True) for td in a]
        return self.cre_list_remove_string(text_list, end_str='階')

    # 建物名の取得
    def get_names(self, soup):
        names = soup.find_all(class_="cassetteitem_content-title")
        # names = self.get_info("cassetteitem_content-title")
        # print(names)
        return self.cre_list(names)

    # 住所
    def get_address(self, soup):
        address = soup.find_all(class_="cassetteitem_detail-col1")
        # address = self.get_info("cassetteitem_detail-col1")
        return self.cre_list(address)

    # 賃料
    def get_price(self, soup):
        price = self.cre_list(soup.find_all(class_="cassetteitem_other-emphasis"))
        # price = self.cre_list(self.get_info("cassetteitem_other-emphasis"))
        return self.cre_list_remove_string(price, end_str='万円')

    # 管理費
    def get_administration(self, soup):
        price = self.cre_list(soup.find_all(class_="cassetteitem_price--administration"))
        # price = self.cre_list(self.get_info("cassetteitem_price--administration"))
        return self.cre_list_remove_string(price, end_str='円')

    # 敷金
    def get_deposit(self, soup):
        price = self.cre_list(soup.find_all(class_="cassetteitem_price--deposit"))
        # price = self.cre_list(self.get_info("cassetteitem_price--deposit"))
        return self.cre_list_remove_string(price, end_str='万円')

    # 礼金
    def get_gratuity(self, soup):
        price = self.cre_list(soup.find_all(class_="cassetteitem_price--gratuity"))
        # price = self.cre_list(self.get_info("cassetteitem_price--gratuity"))
        return self.cre_list_remove_string(price, end_str='万円')

    # 間取り
    def get_madori(self, soup):
        madori = self.cre_list(soup.find_all(class_="cassetteitem_madori"))
        # madori = self.cre_list(self.get_info("cassetteitem_madori"))
        value = []
        plan = []
        for i in madori:
            if i == 'ワンルーム':
                value.append(1)
                plan.append('R')
            else:
                value.append(re.search(r'\d+', i).group())
                plan.append(re.search(r'\D+', i).group())
        return value, plan

    # 占有面積
    def get_menseki(self, soup):
        menseki = soup.find_all(class_="cassetteitem_menseki")
        # menseki = self.get_info("cassetteitem_menseki")
        return self.cre_list_split(menseki, 'm', 1, 0)

    # 築年数
    def get_building(self, soup):
        building = self.cre_list_split(soup.find_all(class_="cassetteitem_detail-col3"), '\n', 2, 1)
        # building = self.cre_list_split(self.get_info("cassetteitem_detail-col3"), '\n', 2, 1)
        building[0] = '築0年' if building[0] == '新築' else building[0]
        return self.cre_list_remove_string(building, '築', '年')

    # 最寄駅と駅からの距離を取得
    def get_statuses(self, soup):

        route = []
        station = []
        distance = []
        statuses = soup.find_all(class_="cassetteitem_detail-col2")

        # バスを検索の条件から削除
        status = self.delete_target_string(self.cre_list_split(statuses, '\n'), ['バス', r'車(\d+)'])

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
