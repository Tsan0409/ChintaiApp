import matplotlib.pylab
import pandas as pd
from matplotlib import pyplot as plt
import matplotlib.font_manager
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler


class DeepLearning:

    def __init__(self, main, csv=None, df=None):
        self.test = None
        self.train_val = None
        self.x_train = None
        self.x_val = None
        self.y_train = None
        self.y_val = None
        if csv:
            self.df = pd.read_csv(csv)
        else:
            self.df = df
        self.main_column = main
        # self.df2 = self.df.copy()
        matplotlib.rcParams['font.family'] = 'Hiragino Sans'

    # 文字列を値に変更
    def string_to_value(self, string):
        dummy = pd.get_dummies(self.df[string], drop_first=True, dtype='uint8')
        self.df = pd.concat([self.df, dummy], axis=1)
        self.df = self.df.drop([string], axis=1)
        return

    # ダミー変数になったカラムを削除
    def remove_dummy(self, column, str_column):

        removed_column = []
        for i in column:
            if i in str_column:
                continue
            else:
                removed_column.append(i)

        return removed_column

    # 元のカラムと追加したカラムを結合する
    def marge_column(self, column, add_column, str_column):

        # ダミー変数になったカラムを削除する
        column = self.remove_dummy(column, str_column)

        # カラムを追加する
        for i in add_column:
            column += i
        return column

    # 欠損値を平均値で埋める
    def fill_null_mean(self):
        # 平均値で穴埋めする
        train_val_mean = self.train_val.mean()
        print('平均値')
        self.train_val = self.train_val.fillna(train_val_mean)
        return

    # 欠損値を中央値で埋める
    def fill_null_median(self):
        # 中央値で穴埋めする
        train_val_median = self.train_val.median()
        print('中央値')
        self.train_val = self.train_val.fillna(train_val_median)
        return

    def learning_null(self, column):
        condition = self.train_val[column].isnull()
        non_data = self.train_val.loc[condition]

        x = self.train_val[
            [i for i in self.df.columns.values if i != column]]
        y = self.train_val[[self.main_column]]

        x = non_data.loc[:, '']

    # 外れ値の行を削除する
    # condition には > 30 などの条件部分を代入
    def remove_outlier_manual(self, df, column, condition1, column2=None, condition2=None):
        print(f'<< {column} >>')
        if column in df:

            if not condition2:
                outlier = df[(eval(f'df[column] {condition1}', {}, {'df': df, 'column': column}))].index
                print('外れ値: condition1')
                print(outlier)
                df = df.drop(outlier, axis=0)

            else:
                outlier = df[(eval(f'(df[column] {condition1}) & (df[column2] {condition2})', {}, {'df': df, 'column': column, 'column2': column2}))].index
                print('外れ値: condition1 & condition2')
                df = df.drop(outlier, axis=0)

        else:
            print(f'外れ値: {column} は存在しない')

        return df

    # 図表から外れ値を確認する
    def check_outlier(self):
        colnames = self.train_val.columns
        for name in colnames:
            print(f"Creating scatter plot for column: {name}")
            self.train_val.plot(kind='scatter', x=name, y=self.main_column)
            # plt.show()  # グラフを表示

    # 相関係数を調べる
    def check_corr(self):

        # 列同士の相関係数を調べる
        print(self.train_val.corr())

        # 特定の列との相関係数のみPICK
        train_cor = self.train_val.corr()[self.main_column]

        # 相関係数を順番に並べる
        print('----------------------- 相関係数(降順）------------------')
        abs_cor = train_cor.map(abs)
        print(abs_cor.sort_values(ascending=False))
        return

    # dfを特定のカラムのみに変更する
    def change_df(self, need_column):
        self.df = self.df[need_column]
        return

    # 多項式特徴量
    def polynomial(self, df, column, num):
        df[f'{column}2'] = df[column] ** num
        return df

    # 交互作用特徴量
    def interaction(self, df, column_a, column_b):
        copy_df = df.copy()
        if type(column_a) == list:
            print('type: list')

            for i in column_a:
                copy_df[f'{i} * {column_b}'] = df[i] * df[column_b]
        else:
            print('type: string')
            copy_df[f'{column_a} * {column_b}'] = df[column_a] * df[column_b]
            copy_df[f'{column_a} * {column_b} 2'] = copy_df[f'{column_a} * {column_b}']
        return copy_df

    # データを標準化し、訓練データと検証データを返す
    def change_sc(self, df, val):
        sc_model = StandardScaler()
        sc_model.fit(df)
        sc = sc_model.transform(df)
        # 平均値が0になっているか確認する
        tmp_df = pd.DataFrame(sc, columns=df.columns)
        sc_val = sc_model.transform(val)
        return sc, sc_val, sc_model

    # 学習と採点
    def learn(self, x, y):
        self.x_train, self.x_val, self.y_train, self.y_val\
            = train_test_split(x, y, test_size=0.2, random_state=0)

        # 標準化
        sc_x, sc_x_val, sc_model = self.change_sc(self.x_train, self.x_val)
        sc_y, sc_y_val, sc_model = self.change_sc(self.y_train, self.y_val)

        # モデルの採点
        model = LinearRegression()
        model.fit(sc_x, sc_y)
        print(' << 点数 >>\n', model.score(sc_x_val, sc_y_val))

        return model
