# WordPressテーマ化 参考資料

このファイルは、`docs/wordpress-rules.md` の詳細版です。
構成例、実装例、入れ子のセクション、例外の判断が必要な場合に確認する。

---

## 1. 基本ファイル構成

```txt
my-theme/
├── style.css
├── screenshot.png
├── index.php
├── functions.php
├── header.php
├── footer.php
├── front-page.php
├── home.php
├── page.php
├── single.php
├── archive.php
├── 404.php
├── template-parts/
│   ├── front/
│   ├── common/
│   ├── post/
│   ├── {page-slug}/
│   └── {post_type}/
└── assets/
    ├── css/
    ├── js/
    └── img/
```

`style.css` の先頭には、WordPressがテーマとして認識するためのテーマヘッダーを書く。

```css
/*
Theme Name: Atelier Tomi
Author: Tomi
Version: 1.0.0
*/
```

---

## 2. テンプレート選択の判断基準

作業前に対象ページの種類を確認し、WordPressのテンプレート階層に沿って次のファイルを選ぶ。

| 対象 | 基本テンプレート |
| --- | --- |
| トップページ | `front-page.php` |
| 通常投稿一覧 | `home.php` |
| 通常投稿詳細 | `single.php` |
| カテゴリー・タグ・日付別一覧 | `archive.php` |
| 通常の固定ページ | `page.php` |
| 固有の構成が必要な固定ページ | `page-{slug}.php` |
| カスタム投稿一覧 | `archive-{post_type}.php` |
| カスタム投稿詳細 | `single-{post_type}.php` |
| 404ページ | `404.php` |
| 最後の受け皿 | `index.php` |

実際に存在するページや投稿タイプだけを対象にする。ページの種類や投稿タイプ名を推測して、テンプレートや空のディレクトリを先に作らない。

---

## 3. トップページの構成例

トップページの各セクションは `template-parts/front/` に配置する。

```txt
front-page.php

template-parts/
└── front/
    ├── mv.php
    ├── about.php
    ├── reasons.php
    ├── skills.php
    ├── works.php
    ├── service.php
    ├── price.php
    ├── process.php
    ├── faq.php
    └── contact.php
```

`front-page.php` には、共通ヘッダー、各セクションの読み込み、共通フッターだけを書く。

```php
<?php get_header(); ?>

<main>
  <?php get_template_part( 'template-parts/front/mv' ); ?>
  <?php get_template_part( 'template-parts/front/about' ); ?>
  <?php get_template_part( 'template-parts/front/reasons' ); ?>
  <?php get_template_part( 'template-parts/front/skills' ); ?>
  <?php get_template_part( 'template-parts/front/works' ); ?>
  <?php get_template_part( 'template-parts/front/service' ); ?>
  <?php get_template_part( 'template-parts/front/price' ); ?>
  <?php get_template_part( 'template-parts/front/process' ); ?>
  <?php get_template_part( 'template-parts/front/faq' ); ?>
  <?php get_template_part( 'template-parts/front/contact' ); ?>
</main>

<?php get_footer(); ?>
```

---

## 4. 固定ページの構成例

一般的な固定ページは `page.php` を使用する。ページ固有のデザインやセクション構成が必要な場合に `page-{slug}.php` を使用する。

固定ページ用のパーツは、ページスラッグと同じ名前のディレクトリに配置する。

```txt
page-company.php

template-parts/
└── company/
    ├── mv.php
    ├── profile.php
    ├── history.php
    └── access.php
```

`page-company.php` では、必要なセクションを表示順に読み込む。

```php
<?php get_header(); ?>

<main>
  <?php get_template_part( 'template-parts/company/mv' ); ?>
  <?php get_template_part( 'template-parts/company/profile' ); ?>
  <?php get_template_part( 'template-parts/company/history' ); ?>
  <?php get_template_part( 'template-parts/company/access' ); ?>
</main>

<?php get_footer(); ?>
```

管理画面から本文を編集する設計では、`the_content()` を使用する既存構成を維持する。静的デザインを優先するページでは、既存設計に沿って専用テンプレートを使用する。HTMLが短く単純な固定ページは、無理にセクション分割しない。

---

## 5. 通常投稿の構成例

通常投稿の一覧は `home.php`、詳細は `single.php` を基本とする。カテゴリー、タグ、日付別などの一覧には `archive.php` を使用する。

```txt
home.php
single.php
archive.php

template-parts/
└── post/
    ├── card.php
    └── content.php
```

一覧カードなど繰り返し使う部品は `template-parts/post/` へ配置する。投稿以外でも同じ部品を使用する場合は `template-parts/common/` への共通化を検討する。

```php
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'template-parts/post/card' ); ?>
  <?php endwhile; ?>
<?php endif; ?>
```

投稿詳細のHTMLが大きい場合は、本文表示などを `template-parts/post/` へ分割してよい。既存のWordPressループや `the_title()`、`the_content()`、`the_permalink()` などを静的HTMLへ戻さない。

---

## 6. カスタム投稿タイプの構成例

カスタム投稿タイプの一覧は `archive-{post_type}.php`、詳細は `single-{post_type}.php` を基本とする。専用パーツは `template-parts/{post_type}/` に配置し、投稿タイプ名とディレクトリ名をできるだけ揃える。

投稿タイプが `works` の場合は、次の構成になる。

```txt
archive-works.php
single-works.php

template-parts/
└── works/
    ├── card.php
    ├── content.php
    └── cta.php
```

一覧カード、詳細情報、CTAなどを役割ごとに必要な範囲で分割する。複数のページや投稿タイプで使う部品は `template-parts/common/` への共通化を検討する。カスタム投稿タイプ固有の処理を通常投稿や固定ページへ混在させない。

上記は構成例であり、実際に `works` が存在しないサイトへファイルやディレクトリを作成しない。

テーマ内で `register_post_type()` を管理し、登録処理が大きくなる場合は、次のように役割別ファイルへ分離できる。

```txt
functions.php
inc/
└── post-types.php
```

分離する前に、プラグイン、`functions.php`、`inc/` など既存の登録場所を確認する。別の場所が登録を担当している場合は二重登録しない。rewrite、slug、archive、公開状態、REST API対応などの既存設定を勝手に変更しない。

---

## 7. 命名と配置の判断基準

- ページ固有のパーツは `template-parts/{ページスラッグ}/` に置く。
- 通常投稿用のパーツは `template-parts/post/` に置く。
- カスタム投稿タイプ専用のパーツは `template-parts/{post_type}/` に置く。
- 複数ページで使う共通パーツは `template-parts/common/` への配置を検討する。
- ファイル名は `mv.php`、`profile.php`、`history.php`、`access.php` のように役割で決める。
- 同じ役割には同じ名前を使う。`works.php` と `portfolio.php` のような表記の混在を避ける。
- ページ内で一度しか使わない要素を、理由なく共通パーツにしない。
- `page-{slug}.php` の `{slug}` と `template-parts/{slug}/` の名前をできるだけ揃える。
- `single-{post_type}.php`、`archive-{post_type}.php` の `{post_type}` とパーツのディレクトリ名をできるだけ揃える。
- 実在するページと投稿タイプに必要なディレクトリだけを作る。

---

## 8. 入れ子になったセクション

親セクション内に子セクションがある場合は、分割前に開始タグと終了タグの対応を確認する。

原則として、各テンプレートパーツ内でHTMLタグを完結させる。親パーツ内から子パーツを読み込める場合は、次の構成を優先する。

```php
<section class="about" id="about">
  <div class="about__inner">
    <?php get_template_part( 'template-parts/front/about-profile' ); ?>
    <?php get_template_part( 'template-parts/front/reasons' ); ?>
    <?php get_template_part( 'template-parts/front/skills' ); ?>
  </div>
</section>
```

既存構造やユーザー指定を維持するため、親セクションの開始タグと終了タグが複数のパーツをまたぐ場合は、次を確認する。

- 親子関係と表示順が分割前と同じか。
- 開始タグと終了タグを担当するファイルが明確か。
- パーツの読み込み漏れでHTMLが壊れないか。
- CSSやJavaScriptが参照するDOM構造が変わっていないか。

ユーザーから読み込み場所や順序の指定がある場合は、その指定を優先する。

---

## 9. 分割しない例外

次の場合は、無理にテンプレートパーツへ分割しない。

- HTMLが短く、今後も分割する必要がない単純なページ。
- WordPressのループや条件分岐を分割すると、処理の関係が分かりにくくなる場合。
- フォームなど、関連する処理を同じファイルに残す方が安全な場合。
- 分割によってHTML構造や既存機能を変更する必要がある場合。
- 管理画面から編集する本文と `the_content()` の関係を、同じファイルで示す方が分かりやすい場合。

例外を適用する場合も、既存構造、既存機能、ユーザーの指示を優先する。

---

## 10. 分割後の確認例

- `front-page.php` や `page-{slug}.php` の読み込み順を確認する。
- すべてのテンプレートパーツが存在することを確認する。
- PHP構文チェックを行う。
- 分割前後の出力HTMLを比較する。
- class名、id名、文言、リンク、画像パス、フォーム処理を確認する。
- 投稿一覧、投稿詳細、アーカイブ、固定ページ、カスタム投稿タイプが意図したテンプレートを使用しているか確認する。
- WordPressループ、条件分岐、カスタムフィールド、URL構造、投稿タイプの登録設定が変わっていないことを確認する。
- スマホ・タブレット・PCで表示を確認する。
- メニュー、FAQ、アニメーション、フォームなどの動作を確認する。
