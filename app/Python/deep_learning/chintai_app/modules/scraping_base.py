import time

import requests
import pandas as pd

from bs4 import BeautifulSoup

# スクレイピングのベースコード
class ScrapingBaseClass:

    def __init__(self, csv):
        self.csv_name = csv
        
    # htmlを返す
    def get_html(self, url):
        time.sleep(3)
        html = requests.get(url)
        soup = BeautifulSoup(html.content, "html.parser")
        return soup

    # 総ページ数の返す
    def get_total_page(self, soup,class_name, tag):
        # HTMLが取得できなかった場合の処理
        pages = soup.find(class_=class_name).find_all(tag)
        total_pages = int(pages[-1].text) if pages else None
        print('total_pages', total_pages)
        return total_pages

    # URLにページ数が存在しない場合、1ページ目であることを明記する
    def append_page_format(self, url):
        if "page=" not in url:
            format_url = url + "&page=1"
        return format_url

    # 次のページのURLを返す
    def increment_current_page(self, url, current_page):
        next_page = current_page + 1
        next_page_url = url.replace(f"page={current_page}", f"page={next_page}")
        return next_page_url

    # htmlの要素から指定クラスの文字列のみを取り出す
    def get_property_details(self, soup, class_name, start_target_string=None, end_target_string=None):
        got_target_element = soup.find_all(class_=class_name)
        target_strings = self.create_list(got_target_element)
        
        # 物件を取り除く引数が入れられていない場合
        if start_target_string is None and end_target_string is None: 
            return target_strings
        else:
            return self.create_list_remove_string(target_strings, start_target_string, end_target_string)
            

    # htmlタグからテキストのみ取り出す
    def create_list(self, info_tab):
        return [i.text for i in info_tab]

    # 文字列から複数のリストを作成する
    def create_list_split(self, info_tab, st, num_str=None, num_list=None):

        # 区切り文字を指定してリストに返す
        if num_list is None and num_str is None:
            return [i.text.split(f'{st}') for i in info_tab]
        # 区切り文字と最大分割回数を指定してリストに返す
        else:
            return [i.text.split(f'{st}', num_str)[num_list] for i in info_tab]

    #  リスト内の文字列から指定文字を切り取る
    def create_list_remove_string(self, info_tab, start_target_string=None, end_target_string=None):
        if start_target_string is None:
            return [i[0:i.find(end_target_string)] for i in info_tab]
        elif end_target_string is None:
            return [i[i.find(start_target_string) + 1:] for i in info_tab]
        else:
            return [i[i.find(start_target_string) + 1:i.find(end_target_string)] for i in info_tab]


    # 項目ごとに取得したリストを整理してCSV化する
    def transposed_data(self, column_name, scraped_data_list):
        transposed_data = list(zip(*scraped_data_list))
        df = pd.DataFrame(transposed_data, columns=column_name)
        df.to_csv(self.csv_name, index=False)
        return
    
