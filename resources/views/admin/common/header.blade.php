
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="Learn how to quickly get started using our CSS framework. We have guides for a variety of skill levels.">
    <title>競合防止システム管理画面</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Noto+Sans+JP:400,700" rel="stylesheet">
    <link rel="stylesheet" href="/css/flatpickr.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="/js/flatpickr.js"></script>
  </head>
  <body>
    <header>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">ブリリアント</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="/index.php/admin/user/index">会員情報一覧</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php/admin/user/create">会員情報登録</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/index.php/admin/user/all">取り込み済みCSVデータ一覧</a>
              </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php/admin/event">イベント情報一覧</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php/admin/import">CSVのインポート</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php/admin/event/log/100/0">ログ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/" target="_blank">新規ウィンドウで開く(動作不良時)</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <div class="container brilliant-block">
      <div class="row justify-content-between">
        <div class="col-2">
          <p><button id="previous" class="btn btn-outline-dark">
            <<前のページへ戻る
          </button></p>
        </div>
        <div class="col-2">
          <p><button id="next" class="btn btn-outline-dark">
            次のページへ進む>>
          </button></p>
        </div>
      </div>
    </div>
