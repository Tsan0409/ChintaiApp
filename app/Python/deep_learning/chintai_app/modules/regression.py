import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler

from .deep_learning import DeepLearning
# from deep_learning import DeepLearning
from .learning_detail import LearningNull, LearningOutlier


class RegressionAnalysis(DeepLearning):

    def __init__(self, csv, column_name, str_column, target_data, plan):

        # 全てのカラム（一番最初に入れる値は正解データ）
        self.column_names = column_name
        self.str_column = str_column
        self.main_column = '賃料'
        self.add_column = []
        self.train_val = None
        self.csv = csv
        self.target_data = target_data
        self.plan = plan

        super().__init__(self.main_column, csv=csv)

    def learning(self):

        self.change_df(self.column_names)

        print('\n------------------------- 文字列を処理する ------------------------\n')
        
        n = 0
        for i in self.column_names:
            if i in self.str_column and n == 0:
                self.string_to_value(i)
                self.add_column.append([i for i in self.df.columns.values if i not in self.column_names and i not in self.add_column])
                n += 1
            elif i in self.str_column and n >= 1:
                self.string_to_value(i)
                self.add_column.append([i for i in self.df.columns.values if i not in self.column_names and i not in self.add_column[n-1]])
                n += 1

        # テストデータと訓練データに分ける
        self.train_val, self.test = train_test_split(self.df, test_size=0.2, random_state=0)
        self.train_val = self.train_val.reset_index()

        print('\n------------------------- 欠損値を処理する ------------------------\n')

        null_list = self.remove_dummy(self.column_names, self.str_column)

        # 欠損値の自動除去
        for i in null_list:
            print(f"<< {i} >>")

            # 訓練データの欠損値を除去
            learning_null = LearningNull(self.train_val, i, self.column_names, self.str_column, self.add_column, self.main_column)
            filled = learning_null.fill_null()
            self.train_val = self.train_val if type(filled) == int else filled

            # テストデータの欠損値を除去
            learning_null = LearningNull(self.test, i, self.column_names, self.str_column, self.add_column, self.main_column)
            filled = learning_null.fill_null()
            self.test = self.test if type(filled) == int else filled

        print('\n------------------------- 外れ値を処理する ------------------------\n')

        learning_outlier = LearningOutlier(self.train_val, self.main_column, self.column_names, self.str_column, self.add_column)
        self.train_val = learning_outlier.maharanobisu()
        # self.check_outlier()

        print('\n------------------------- 自動処理終了 ----------------------------\n')

        # 訓練データと検証データに分ける
        print('\nテストデータと検証データに分ける')
        x = self.train_val[[i for i in self.df.columns.values if i != self.main_column]]
        y = self.train_val[[self.main_column]]

        # 学習を開始する
        model = self.learn(x, y)
        
        #  テストデータの作成
        x_test = self.test[[i for i in self.df.columns.values if i != self.main_column]]
        y_test = self.test[[self.main_column]]

        # テストデータの標準化
        sc_model_x = StandardScaler()
        sc_model_x.fit(x_test)
        sc_x = sc_model_x.transform(x_test)

        sc_model_y = StandardScaler()
        sc_model_y.fit(y_test)
        sc_y = sc_model_y.transform(y_test)

        print(' << 点数 >>\n', model.score(sc_x, sc_y))

        # 現状のデータフレームのカラム名を取得する
        new_column_names = self.df.columns.values[1:]

        plans_array = []
        for i in new_column_names[4:]:
            if self.plan == i:
                plans_array.append('1')
            else:
                plans_array.append('0')
                
        merged_data = [self.target_data + plans_array]
        sample = pd.DataFrame(data=merged_data, columns=new_column_names)

        sa = sc_model_x.transform(sample)
        for i in sa:
            print(i)
        print(sa)
        print(sc_model_y.inverse_transform(model.predict(sa)))
        return sc_model_y.inverse_transform(model.predict(sa))
