# コーディング参考資料

このファイルは、`docs/coding-rule.md` の詳細版です。
毎回必ず守る短いルールは `docs/coding-rule.md` を確認する。
このファイルは、理由・OK例・NG例を確認したいときに参照する。

---

## 1. 基本方針

実装では、デザインを崩さず、既存コードの書き方に合わせることを優先する。
大きく作り替えるより、必要な箇所だけを安全に修正する。

### OK

```txt
修正前にHTML構造とSCSS構成を確認する。
既存クラス名や既存のネスト構造に合わせる。
必要な箇所だけを変更する。
```

### NG

```txt
見た目を勝手に変える。
不要なクラスやJavaScriptを追加する。
既存の命名規則を無視して新しい書き方を混ぜる。
```

---

## 2. BEMとHTML構造

クラス名はBEMを基本にする。
セクションの中身は `__inner` に入れ、横幅と左右余白を管理しやすくする。

### OK

```html
<section class="service">
  <div class="service__inner">
    <h2 class="service__title">タイトル</h2>
    <p class="service__text">テキスト</p>
  </div>
</section>
```

```scss
.service {
  &__inner {
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 20px;
  }

  &__title {
  }

  &__text {
  }
}
```

### ポイント

- 背景を画面いっぱいにしたい場合は、section側に背景を指定する。
- テキスト、カード、ボタンなどの中身は `__inner` の中に入れる。
- `__inner` は `max-width`、`margin: 0 auto;`、`padding` を基本にする。

---

## 3. SCSSの配置と記述順

SCSSは既存のフォルダ構成に合わせる。
HTML上で上から出てくる要素の順番に合わせてSCSSを書く。

### フォルダの役割

```txt
scss/
├── foundation/          全体の土台、変数、リセット
├── layout/              ヘッダー、フッターなどの大枠
├── object/
│   ├── component/       共通パーツ
│   └── project/         ページ固有のセクション
└── style.scss
```

### OK

```scss
.about {
  &__inner {
  }

  &__title {
  }

  &__text {
  }

  &__button {
  }
}
```

### NG

```scss
.about {
  &__button {
  }

  &__text {
  }

  &__inner {
  }
}
```

---

## 4. レスポンシブ

このプロジェクトではスマホファーストで実装する。
通常のSCSSにはスマホ用のスタイルを書き、タブレット以上・PC以上はmixinで調整する。

### ブレイクポイント

```scss
$sp: 767;
$tab: 768;
$lt: 1024;
$pc: 1440;
```

### mixinの使い方

- 通常のSCSSをスマホ用の土台として書く。
- `v.sp`は原則使わず、767px以下だけを例外的に上書きする場合に使用を検討する。
- タブレット以上は `@include v.tab`、PC寄りは `@include v.lt` で調整する。


### OK

```scss
.mv {
  min-height: v.fluid(820, 1000, 375, 767);

  @include v.tab {
    min-height: v.fluid(1000, 1300, 768, 1023);
  }

  @include v.lt {
    min-height: 700px;
  }
}
```

### NG

```scss
.mv {
  min-height: v.fluid(820, 1000, 375, 767);
}

@include v.tab {
  .mv {
    min-height: v.fluid(1000, 1300, 768, 1023);
  }
}
```

### 理由

レスポンシブ設定を対象クラス内に書くと、通常時と各画面幅の指定をまとめて確認できる。
あとから修正するときに対象スタイルを探しやすくなる。

---

## 5. 可変サイズと `v.fluid()`

画面幅に応じて自然に変化させたい値は、Sass関数の `v.fluid()` を使う。
手書きの `clamp()` は原則使わない。

### 基本形

```scss
v.fluid(最小サイズ, 最大サイズ, 最小画面幅, 最大画面幅)
```

- `v.fluid()`の第3・第4引数は、その指定を書いているレスポンシブブロックのブレイクポイントに揃える。

### OK

```scss
.service {
  padding-block: v.fluid(60, 120, 375, 1440);

  &__title {
    font-size: v.fluid(28, 48, 375, 1440);
    margin-bottom: v.fluid(24, 48, 375, 1440);
  }

  &__list {
    gap: v.fluid(24, 40, 375, 1440);
  }
}
```

### NG

```scss
.service {
  padding-block: clamp(60px, 8vw, 120px);

  &__title {
    font-size: v.fluid(28, 48);
  }
}
```

### NGの理由

- 手書きの `clamp()` は、プロジェクト内で指定方法がバラつくため使わない。
- `v.fluid(28, 48)` は関数のデフォルト引数があるため動くが、どの画面幅で変化するかが分かりにくい。
- 明示性のため、`v.fluid()` は4引数に統一する。


### 判断基準

- スマホからPCまで自然に変化させたい場合は `v.fluid()` を使う。
- `v.fluid()` は原則4引数で書く。
- 第3・第4引数を省略しても動くが、どの画面幅で変化するかを明確にするため、4引数に統一する。
- min/maxの差が10px以下の場合や、vwの値が極端になる場合は固定値でもよい。
- すべてを可変にする必要はない。

---

## 6. 色の管理

色はなるべく `foundation/_variables.scss` に登録し、各SCSSでは `var(--xxx)` で指定する。
各SCSSファイル内にカラーコードを直接書かない。

### OK

```scss
.card {
  color: var(--ink);
  background: var(--white);
  box-shadow: var(--card-shadow);
}
```

### NG

```scss
.card {
  color: #333333;
  background: #ffffff;
  box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
}
```

---

## 7. 画像の使い分け

画像は役割によってHTMLの `img` とCSSの `background-image` を使い分ける。

### HTMLの `img` を使うもの

- 実績画像
- 商品画像
- スタッフ写真
- 内容として意味がある画像
- 操作や内容の理解に必要なアイコン

### `alt`属性の使い分け

- 画像自体が内容を伝える場合は、内容が分かる `alt`を設定する。
- 文字の横にある装飾アイコンは、`alt=""`と`aria-hidden="true"`を設定する。
- 親のリンクに適切な`aria-label`がある場合、リンク内の実績画像は`alt=""`でよい。
- 同じ内容を `alt` と `aria-label` の両方へ書かず、重複して読み上げられないようにする。

### CSSの `background-image` を使うもの

- MVの背景画像
- セクション背景
- 装飾目的の模様やパーツ
- SEOやアクセシビリティ上、画像として読ませる必要がないもの

### OK

```html
<img
  class="about__photo-img"
  src="assets/img/profile.webp"
  alt="藤本 富大のプロフィール写真"
>

<img
  class="header__cta-icon"
  src="assets/img/mail.svg"
  alt=""
  aria-hidden="true"
>

<a href="..." aria-label="コンサートLPの制作実績を見る">
  <img
    class="works__image-img"
    src="assets/img/works.webp"
    alt=""
  >
</a>
```

```scss
.mv {
  background-image: url("../img/mv.webp");
  background-position: center top;
  background-size: cover;
}
```

---

## 8. タグ指定ではなくクラス指定

SCSSでは、`article`、`h3`、`h4`、`p`、`img` などのタグ名に直接スタイルを当てすぎない。
基本はBEMのクラス名を付けて、そのクラスに対してスタイルを書く。

### OK

```html
<article class="about__reason-card">
  <h4 class="about__reason-title">採用に強いサイト設計</h4>
  <p class="about__reason-text">求職者に伝わる構成・導線で応募数アップをサポート</p>
</article>
```

```scss
.about {
  &__reason-card {
    padding: 20px 26px;
  }

  &__reason-title {
    font-size: 18px;
  }

  &__reason-text {
    font-size: 14px;
  }
}
```

### NG

```scss
.about__reason-list {
  article {
    padding: 20px 26px;
  }

  h4 {
    font-size: 18px;
  }

  p {
    font-size: 14px;
  }
}
```

---

## 9. 共通化しすぎない

SCSSでは、同じスタイルだけをまとめる。
見た目や役割が違う要素を無理にまとめない。

### 共通化してよい例

```scss
.about {
  &__reason-card,
  &__skill-card {
    border-radius: 8px;
    background: var(--white);
    box-shadow: var(--card-shadow);
  }
}
```

### 共通化しない方がよい例

```scss
.about {
  &__reason-card,
  &__skill-card {
    padding: 20px 26px;
    text-align: center;
  }
}
```

### 理由

理由カードとスキルカードで余白・配置・テキスト位置が違う場合、共通化すると片方だけ調整しにくくなる。
迷ったら無理に共通化しない。

---

## 10. 同じクラス名を使い回さない

見た目・役割・余白・配置・サイズが違う要素には、同じクラス名を使い回さない。

### OK

```html
<h4 class="about__reason-title">採用に強いサイト設計</h4>
<h4 class="about__skill-title">デザイン</h4>
```

### NG

```html
<h4 class="about__card-title">採用に強いサイト設計</h4>
<h4 class="about__card-title">デザイン</h4>
```

### 判断基準

同じクラス名を使ってよい場合:

- 見た目が完全に同じ
- 余白が同じ
- 配置が同じ
- 文字サイズが同じ
- 今後も同じデザインとして扱う

同じクラス名を使わない場合:

- 見た目が違う
- 画像やアイコンの位置が違う
- 中央寄せと左寄せで違う
- カード幅が違う
- 片方だけ修正する可能性がある

---

## 11. JavaScript

JavaScriptは必要な場合だけ使う。
HTMLとCSSだけで対応できる場合は、無理にJavaScriptを使わない。

### ドロワー管理のOK例

```js
const headerToggle = document.querySelector(".header__toggle");

const openDrawer = () => {
  document.body.classList.add("is-drawer-open");
  headerToggle?.setAttribute("aria-expanded", "true");
};

const closeDrawer = () => {
  document.body.classList.remove("is-drawer-open");
  headerToggle?.setAttribute("aria-expanded", "false");
};
```

### NG

```js
header.classList.add("is-open");
headerToggle.classList.add("is-active");
document.body.classList.add("is-drawer-open");
```

### 理由

同じ開閉状態を複数のクラスで管理すると、HTML・SCSS・JavaScriptの対応関係が分かりにくくなる。
基本は `body.is-drawer-open` の1つで管理する。

---

## 12. 不要な実装を追加しない

依頼内容を満たすために必要なコードだけを書く。
既存HTML・SCSS・JavaScriptで対応できる場合は、新しい仕組みを作らず、既存の構成を優先する。
新しいコード、クラス、JavaScript、アニメーション、ライブラリを追加する場合は、それが必要な理由が明確なときだけにする。

### OK

```txt
依頼された箇所だけ修正する。
既存のクラス名とSCSS構成に合わせて調整する。
HTMLとCSSだけで対応できる場合はJavaScriptを追加しない。
既存で使っている共通クラスや変数を使う。
追加が必要な場合でも、最小限のHTML・SCSS・JavaScriptにする。
```

### NG

```txt
依頼されていないセクションを作り替える。
不要なアニメーションを追加する。
使っていないライブラリを追加する。
既存にない命名ルールや状態クラスを増やす。
一度しか使わない処理を無理に共通化する。
既存の仕組みで対応できるのに、新しいコンポーネントやJavaScriptを作る。
```

### 判断基準

- そのコードがないと依頼内容を満たせないか。
- 既存のHTML・SCSS・JavaScriptで対応できないか。
- 追加したコードを他の箇所でも使う明確な理由があるか。
- デザインや動作を勝手に変えていないか。
- 迷った場合は、追加せず既存の書き方に合わせる。

---

## 13. 修正後の確認

修正後は、変更内容に応じて以下を確認する。

- Sassを変更したら生成CSSも更新したか。
- 古いクラスや不要なセレクタが残っていないか。
- `v.fluid()` が4引数になっているか。
- 手書きの `clamp()` が不要に残っていないか。
- `is-open`、`is-active`、`is-drawer-open` が同じ意味で混在していないか。
- スマホ・タブレット・PC幅で表示が崩れていないか。
