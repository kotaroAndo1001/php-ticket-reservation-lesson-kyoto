# php-ticket-reservation-prototype

## 演習環境の準備

この教材では、GitHub上の教材リポジトリを `git clone` せず、ZIPファイルとして取得します。

取得したソースコードを各自のGitリポジトリとして初期化し、自分のGitHubアカウント上のリポジトリへpushしてください。

### 1. 教材をZIPでダウンロードする

VPSへログインしたあと、作業したいディレクトリへ移動して、以下を実行します。

```bash
wget -O lesson.zip https://github.com/gallu/php-ticket-reservation-lesson-kyoto/archive/refs/heads/master.zip
```

### 2. ZIPファイルを展開する

```bash
unzip lesson.zip
rm lesson.zip
```

展開すると、以下のディレクトリが作成されます。

```text
php-ticket-reservation-lesson-kyoto-master
```

このディレクトリへ移動します。

```bash
cd php-ticket-reservation-lesson-kyoto-master
```

ライブラリをインストールします。

```
composer install
```

### 3. Gitリポジトリとして初期化する

この時点では、まだ自分のGitリポジトリにはなっていません。

以下を実行してGit管理を開始します。

```bash
git init
git add .
git commit -m "Initial commit"
```

### 4. GitHub上に自分のリポジトリを作成する

GitHubへログインし、自分のアカウント上に新しいリポジトリを作成してください。

このとき、README、`.gitignore`、LICENSEなどは追加せず、空のリポジトリとして作成してください。

リポジトリ名は、授業内で指定がある場合はその名前を使用してください。

### 5. 自分のGitHubリポジトリを登録する

GitHub上で作成したリポジトリのSSH URLを確認し、以下のように登録します。

`USERNAME` と `REPOSITORY` は、自分のGitHubユーザー名とリポジトリ名へ置き換えてください。

```bash
git remote add origin git@github.com:USERNAME/REPOSITORY.git
```

登録内容を確認します。

```bash
git remote -v
```

### 6. GitHubへpushする

ブランチ名を `main` に変更し、GitHubへpushします。

```bash
git branch -M main
git push -u origin main
```

正常に完了したら、GitHub上で自分のリポジトリを開き、教材のファイルが登録されていることを確認してください。

---

### 以降の作業

以降は、このディレクトリ内で授業の課題を進めます。

変更をGitHubへ反映するときは、基本的に以下の流れで操作します。

```bash
git status
git add .
git commit -m "変更内容がわかるメッセージ"
git push
```

教材の元リポジトリではなく、**必ず自分のGitHubリポジトリへpushしていることを確認してください。**

---

## 動かし方

```
cd ~/php-ticket-reservation-lesson-kyoto-master
php -S 0.0.0.0:貸与されたport番号 -t public
```
