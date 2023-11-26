import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler

from .deep_learning import DeepLearning
# from deep_learning import DeepLearning
from .learning_detail import LearningNull, LearningOutlier


class RegressionAnalysis(DeepLearning):

    def __init__(self, csv, column_name, str_column, target_data):

        # 全てのカラム（一番最初に入れる値は正解データ）
        self.column_names = column_name
        self.str_column = str_column
        self.main_column = '賃料'
        self.add_column = []
        self.train_val = None
        self.csv = csv
        self.target_data = target_data

        super().__init__(self.main_column, csv=csv)

    def learning(self):

        self.change_df(self.column_names)

        # 駅名の入っていない行を削除する
        # not_eki = self.df['最寄駅'].str.contains('駅')
        # a = not_eki[not_eki == False].index
        # self.df = self.df.drop(a, axis=0)
        # self.df = self.df.dropna(subset=['最寄駅'], how='all')
        # print(self.df)

        print('\n------------------------- 文字列を処理する ------------------------\n')

        print('カラム一覧: self.column_names\n', self.column_names)
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
        print(self.df)
        print('追加カラム一覧: self.add_column\n', self.add_column)

        # テストデータと訓練データに分ける
        self.train_val, self.test = train_test_split(self.df, test_size=0.2, random_state=0)
        self.train_val = self.train_val.reset_index()

        print('\n------------------------- 欠損値を処理する ------------------------\n')

        null_list = self.remove_dummy(self.column_names, self.str_column)
        print(f'null_list\n{null_list}')

        print(self.train_val)

        # 欠損値の自動除去
        for i in null_list:
            print(f"<< {i} >>")

            # 訓練データの欠損値を除去
            learning_null = LearningNull(self.train_val, i, self.column_names, self.str_column, self.add_column, self.main_column)
            filled = learning_null.fill_null()
            self.train_val = self.train_val if type(filled) == int else filled

            # テストデータの欠損値を除去
            learning_null = LearningNull(self.test, i, self.column_names,
                                         self.str_column, self.add_column,
                                         self.main_column)
            filled = learning_null.fill_null()
            self.test = self.test if type(filled) == int else filled

        # 欠損値を平均値で埋める
        # self.fill_null_mean()

        # 欠損値を中央値で埋める
        # self.fill_null_median()

        print('\n------------------------- 外れ値を処理する ------------------------\n')

        learning_outlier = LearningOutlier(self.train_val, self.main_column, self.column_names, self.str_column, self.add_column)
        self.train_val = learning_outlier.maharanobisu()
        # self.check_outlier()

        print('\n------------------------- 自動処理終了 ----------------------------\n')
        print(self.train_val)

        # 訓練データと検証データに分ける
        print('\nテストデータと検証データに分ける')
        x = self.train_val[[i for i in self.df.columns.values if i != self.main_column]]
        y = self.train_val[[self.main_column]]

        # 学習を開始する
        print('\n性能向上チューニング前')
        self.learn(x, y)

        # 結果向上のためチューニングする

        # print('\n交互作用特徴量追加')
        # x = self.interaction(x, self.add_column[0], '面積')
        # x = self.interaction(x, self.add_column[0], '部屋数')
        # x = self.interaction(x, '部屋数', '面積')
        # self.learn(x, y)
        # #
        # print('\n多項式特徴量追加')
        # x = self.polynomial(x, '部屋数', 7)
        # x = self.polynomial(x, '距離', 3)
        #
        # self.learn(x, y)
        # for i in self.add_column[0]:
        #     x = self.polynomial(x, f'{i} * 面積', 2)
        #
        # for i in self.add_column[0]:
        #     x = self.polynomial(x, f'{i} * 部屋数', 4)
        #
        # x = self.polynomial(x, '部屋数 * 面積', 2)

        # for n in range(10):
        #     print(f'{n+2}乗')
        #     for i in self.add_column[0]:
        #         x = self.polynomial(x, f'{i} * 部屋数', n+2)
        #     self.learn(x, y)

        print('\n チューニング終了後')
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

        sample = pd.DataFrame(self.target_data, columns=['部屋数', '面積', '距離', '築年数', 'K', 'LDK', 'R', 'SDK', 'SK', 'SLDK'])

        sa = sc_model_x.transform(sample)
        for i in sa:
            print(i)
        print(sa)
        print(sc_model_y.inverse_transform(model.predict(sa)))
        return sc_model_y.inverse_transform(model.predict(sa))


all_columns = ['最寄駅', '距離', '築年数', '賃料', '面積', 'プラン', '部屋数', '管理費', '敷金', '礼金']
need_columns = ['賃料', '部屋数', 'プラン', '面積', '距離', '築年数']
str_columns = ['プラン']

# 部屋数 面積  距離 築年数 K  LDK  R  SDK  SK  SLDK
sample_data = [[2, 52, 40, 55, 0, 1, 0, 0, 0, 0]]

# research = RegressionAnalysis('Takatsuki3.csv', need_columns, str_columns, sample_data)
# research.learning()

# 平均値の場合
# 0.6388903582387467

# 中央値の場合
