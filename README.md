# WordPress Docker ローカル環境

Docker Composeを使用して、WordPressとMySQLをPC内に構築するためのプロジェクトです。

## 必要なもの

- Docker Desktop
- Git

## 初回の起動方法

### 1. リポジトリを取得する

```powershell
git clone https://github.com/tomifujimoto/wordpress-docker.git
cd wordpress-docker
```

### 2. `.env`を作成する

Windows PowerShellでは、`.env.example`をコピーして`.env`を作成します。

```powershell
Copy-Item .env.example .env
```

macOSまたはLinuxでは、次のコマンドを使用します。

```bash
cp .env.example .env
```

### 3. `.env`の4項目を設定する

作成した`.env`を開き、4つの値を入力します。

```env
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=任意のパスワード
MYSQL_ROOT_PASSWORD=任意の管理者パスワード
```

`MYSQL_PASSWORD`と`MYSQL_ROOT_PASSWORD`には、自分の環境で使用するパスワードを設定してください。`.env`はGitの管理対象外です。

### 4. Dockerを起動する

```powershell
docker compose up -d
```

MySQLのヘルスチェックが完了すると、WordPressが起動します。

### 5. WordPressを開く

ブラウザで次のURLへアクセスします。

```text
http://localhost:8080
```

初回アクセス時にWordPressのセットアップ画面が表示された場合は、画面の案内に沿って設定します。

## 固定ページの作成

新しいWordPress環境では、管理画面から次の3つの固定ページを作成します。固定ページがない場合、フッターのリンク先やフォームの送信完了ページが404になります。

| ページタイトル | スラッグ | 使用されるテンプレート |
| --- | --- | --- |
| プライバシーポリシー | `privacy` | `page-privacy.php` |
| 特定商取引法に基づく表記 | `legal` | `page-legal.php` |
| 送信完了 | `thanks` | `page-thanks.php` |

1. WordPress管理画面の「固定ページ」から「新規固定ページを追加」を開きます。
2. 表にあるページタイトルを入力します。
3. URLのスラッグを、表にある半角英字へ設定します。
4. テンプレートは「デフォルトテンプレート」のままにします。
5. 本文は入力せず、ページを公開します。
6. 同じ手順で3ページすべてを作成します。

各ページの本文とデザインはテーマ内の専用テンプレートに記載されています。スラッグが異なると専用テンプレートが選択されないため、表と同じ値を設定してください。

## 停止方法

```powershell
docker compose down
```

この操作では、MySQLのデータを保存している`db_data`ボリュームは削除されません。

## 環境変数ファイル

- `.env`：実際の接続情報を保存するファイル。GitHubへ登録しません。
- `.env.example`：必要な設定項目を示す見本。GitHubへ登録します。
# wordpress-docker

Dockerで構築したWordPress開発環境です。

## 構成

- WordPress
- MySQL
- Docker Compose
- 自作WordPressテーマ

## 自作テーマ

テーマは以下にあります。

`wordpress/wp-content/themes/tomi`
