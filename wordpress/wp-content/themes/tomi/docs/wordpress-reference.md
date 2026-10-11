# WordPressテーマ化 参考資料

このファイルは、`docs/wordpress-rules.md` の詳細版です。
構成例、実装例、入れ子のセクション、例外の判断が必要な場合に確認する。

このテーマは、ポートフォリオ固有の実装であると同時に、今後のWordPressテーマ制作で参照できる実装見本として管理する。別サイトへ応用する場合は、ページ名、スラッグ、文言、セクション名を対象サイトに合わせつつ、テンプレート階層、パーツの責任範囲、WordPress関数、確認手順の考え方を引き継ぐ。

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

`front-page.php` には、共通ヘッダー、ページ直下に並ぶ各セクションの読み込み、共通フッターだけを書く。`reasons.php` と `skills.php` は `about.php` の子セクションなので、`front-page.php` から直接読み込まない。

```php
<?php get_header(); ?>

<main>
  <?php get_template_part( 'template-parts/front/mv' ); ?>
  <?php get_template_part( 'template-parts/front/about' ); ?>
  <?php get_template_part( 'template-parts/front/works' ); ?>
  <?php get_template_part( 'template-parts/front/service' ); ?>
  <?php get_template_part( 'template-parts/front/price' ); ?>
  <?php get_template_part( 'template-parts/front/process' ); ?>
  <?php get_template_part( 'template-parts/front/faq' ); ?>
  <?php get_template_part( 'template-parts/front/contact' ); ?>
</main>

<?php get_footer(); ?>
```

`about.php` は、プロフィールの後に子セクションを読み込み、開始タグから終了タグまでを同じファイル内で完結させる。

```php
<section class="about" id="about">
  <div class="about__inner">
    <!-- プロフィール -->
    <?php get_template_part( 'template-parts/front/reasons' ); ?>
    <?php get_template_part( 'template-parts/front/skills' ); ?>
  </div>
</section>
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

ページテンプレートはページ直下に並ぶ親セクションだけを読み込む。子セクションは、その子を内包する親パーツから読み込む。これにより、ページテンプレートで子パーツの読み込みを外したり順番を変更したりしても、別の親セクションまで巻き込むタグ崩れを防げる。

### 避ける構成

次のように、親セクションの開始タグと終了タグを別ファイルへ分けない。

```php
// about.php
<section class="about" id="about">
  <div class="about__inner">
    <!-- プロフィール -->

// skills.php
    <!-- スキル -->
  </div>
</section>
```

この構成では、`skills.php` が読み込まれない場合や読み込み順が変わった場合に、`about` セクションが閉じなくなる。

### 推奨する構成

親パーツに開始タグ、子パーツの読み込み、終了タグをまとめ、各ファイルの責任範囲を明確にする。

```php
// about.php
<section class="about" id="about">
  <div class="about__inner">
    <!-- プロフィール -->
    <?php get_template_part( 'template-parts/front/reasons' ); ?>
    <?php get_template_part( 'template-parts/front/skills' ); ?>
  </div>
</section>
```

`reasons.php` と `skills.php` は、それぞれ自身の子セクションだけを開始から終了まで記述する。親である `about` の終了タグは持たせない。`front-page.php` は `about.php` だけを読み込み、子パーツを重ねて直接読み込まない。

この考え方は `about` 固有ではない。サービス内の料金表、会社情報内の沿革、採用情報内の募集要項など、親子関係を持つセクションにも同じように適用する。

### 判断と確認

分割するときは、次を確認する。

- 親子関係と表示順が分割前と同じか。
- 親パーツだけを読み込んでも、親の開始タグと終了タグが揃うか。
- 子パーツが親の終了タグを持っていないか。
- ページテンプレートと親パーツから、同じ子パーツを二重に読み込んでいないか。
- 子パーツの読み込み漏れや順番変更で、後続セクションのHTML構造が壊れないか。
- CSSやJavaScriptが参照するDOM構造が変わっていないか。

既存サイトに開始タグと終了タグをまたぐ構成がある場合は、見本としてそのまま踏襲せず、表示と機能を変えずに親パーツ内で完結できるか検討する。ユーザーから構造の維持を明示され、変更できない場合だけ例外とし、依存関係を文書化して読み込み漏れを確認する。

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

---

## 11. 固定ページと環境の再現

### 固定ページの本文をどこで管理するか

固定ページを作るときは、本文の管理場所を先に決める。

| 設計 | 本文の保存場所 | 基本テンプレート | 向いているページ |
| --- | --- | --- | --- |
| 管理画面で編集する | WordPressのデータベース | `page.php` と `the_content()` | 運用中に担当者が更新するページ |
| テーマで管理する | Gitで管理するPHPファイル | `page-{slug}.php` | デザインと本文を一体で再現するページ |

管理画面型では、テーマをGitから取得しても本文は復元されない。テーマ管理型では、本文とデザインをGitから復元できるが、URLをWordPressへ登録するための固定ページは別途必要になる。

### 専用固定ページの実例

このテーマでは、次の専用テンプレートを実装見本として使用する。

| ページ | スラッグ | テーマファイル | 本文の管理場所 |
| --- | --- | --- | --- |
| プライバシーポリシー | `privacy` | `page-privacy.php` | テーマファイル |
| 特定商取引法に基づく表記 | `legal` | `page-legal.php` | テーマファイル |
| 送信完了 | `thanks` | `page-thanks.php` | テーマファイル |

`page-privacy.php` を置いただけでは `/privacy/` は作成されない。管理画面でスラッグが `privacy` の固定ページを公開すると、WordPressのテンプレート階層によって `page-privacy.php` が選択される。`legal` と `thanks` も同じ考え方とする。

固定ページで別のカスタムテンプレートが選択されている場合は、スラッグ専用の `page-{slug}.php` より先に使用されることがある。専用ファイルを追加した後は、管理画面のテンプレートを「デフォルトテンプレート」にし、実際に選択されたファイルと対象URLを確認する。

### 不要なテンプレートの整理

専用テンプレートへ移行した後も、以前のカスタムテンプレートが残る場合がある。ファイル名だけで不要と判断せず、すべての固定ページのテンプレート割り当てを確認する。どのページにも使用されていないことを確認してから削除する。

### Docker環境を共有する場合

環境変数を使用するDockerプロジェクトでは、次の役割を分ける。

| ファイル | 役割 | Gitで管理するか |
| --- | --- | --- |
| `.env` | 実際のデータベース名、ユーザー名、パスワード | 管理しない |
| `.env.example` | 必要な環境変数名を示す空の見本 | 管理する |
| `.gitignore` | `.env` がGitへ入ることを防ぐ | 管理する |
| `README.md` | コピー、値の設定、起動、固定ページ作成の手順 | 管理する |
| `docker-compose.yml` | `.env` の値を参照してコンテナを定義する | 管理する |

実際のコマンドやこのプロジェクト固有の値は、プロジェクト直下の `README.md` を正本とする。この参考資料では役割と判断基準だけを扱い、READMEと同じ手順を重複させない。

### 再現性の確認

- `.env` がGitの管理対象外になっているか。
- `.env.example` に必要な環境変数が過不足なく並んでいるか。
- READMEの手順だけでDockerを起動できるか。
- 必要な固定ページ、スラッグ、公開状態がREADMEに記載されているか。
- 各URLが404にならず、想定した `page-{slug}.php` を使用しているか。
- フッターなどの固定ページリンクと、フォーム送信後の移動先が正しいか。

---

## 12. SEOメタ情報の設定

### meta description

`meta description` は、検索結果などでページ内容を説明するための文章として使用される。ページ内容と一致する説明文を設定し、空の値を出力しない。

このテーマでは、次の優先順位で説明文を決める。

1. トップページでは、トップページ専用の説明文を使用する。
2. 投稿・固定ページでは、管理画面に入力された抜粋を使用する。
3. 抜粋が空の場合は、サイト共通の説明文を使用する。

```php
$description = is_front_page()
    ? 'トップページ専用の説明文'
    : wp_strip_all_tags( get_the_excerpt() );

if ( '' === trim( $description ) ) {
    $description = 'サイト共通の説明文';
}
```

共通の説明文は、抜粋の入力漏れによって `meta description` が空になることを防ぐ予備である。すべてのページを同じ説明文にするためのものではない。検索上重要な投稿・固定ページには、そのページの内容を短く表す個別の抜粋または説明文を設定する。

実装後は、トップページ、抜粋があるページ、抜粋がないページのHTMLを確認し、`<meta name="description">` が1つだけ出力され、内容が正しく切り替わることを確認する。SEOプラグインが同じタグを出力する場合は、テーマ側との二重出力を避ける。

### OGP URL

`og:url` は、SNSへ現在のページのURLを伝えるために使用する。投稿一覧やアーカイブでもトップページURLへ固定せず、実際に表示している一覧のURLを設定する。

```php
$url = is_singular()
    ? get_permalink()
    : get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) );
```

- 投稿詳細・固定ページでは、`get_permalink()` でそのページのURLを取得する。
- 投稿一覧・カテゴリー・タグ・日付別一覧では、`get_pagenum_link()` で現在の一覧URLを取得する。
- 2ページ目以降の一覧では、そのページ番号を含むURLを使用する。

実装後は、トップページ、投稿詳細、固定ページ、投稿一覧、カテゴリーなどを表示し、それぞれの `<meta property="og:url">` が表示中のURLに対応していることを確認する。特に投稿一覧の `/news/` やカテゴリー一覧が、トップページのURLになっていないことを確認する。

### OGPの種類

`og:type` は、SNSへページの種類を伝えるために使用する。通常投稿の詳細は記事を表す `article`、それ以外のトップページ・固定ページ・一覧ページは `website` とする。

```php
'og:type' => is_singular( 'post' ) ? 'article' : 'website',
```

この条件では、WordPressの通常投稿だけが `article` になる。固定ページや投稿一覧を誤って `article` にしない。カスタム投稿タイプを記事として扱う場合は、その投稿タイプの役割を確認してから `is_singular( 'post_type' )` などを条件へ追加する。

実装後は、通常投稿の詳細で `<meta property="og:type" content="article">`、トップページ・固定ページ・投稿一覧で `<meta property="og:type" content="website">` が1つだけ出力されることを確認する。
