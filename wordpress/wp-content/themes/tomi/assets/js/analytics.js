// この処理をすぐに実行し、中の変数名が他のコードとぶつからないようにする。
    (() => {
      // GA4（アクセス解析）とClarity（クリック・スクロールなどの行動解析）を開始する処理をまとめる。
      const loadAnalytics = () => {
        // GA4への命令をためる配列を用意する。すでにあれば、その配列を使う。
        window.dataLayer = window.dataLayer || [];
        // gtag関数がなければ作り、渡された引数（arguments）を先ほどの配列にためる。
        window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
        // GA4に、初期化した日時を伝える命令を追加する。
        window.gtag('js', new Date());
        // このサイトの測定IDを指定し、GA4を設定する命令を追加する。
        window.gtag('config', 'G-C9PD8LHRE1');

        // GA4本体を読み込むためのscriptタグを作る。
        const googleTag = document.createElement('script');
        // GA4本体を非同期で取得する。実行時にはブラウザーの処理時間を使う。
        googleTag.async = true;
        // GA4本体の取得先URLを指定する。末尾のIDはこのサイトの測定ID。
        googleTag.src = 'https://www.googletagmanager.com/gtag/js?id=G-C9PD8LHRE1';
        // 作ったタグをhead内に追加し、GA4本体の読み込みを開始する。
        document.head.appendChild(googleTag);

        // Clarityへの命令を受け取る関数を用意する。すでにあれば、それを使う。
        window.clarity = window.clarity || function () {
          // Clarity本体が命令を処理できるよう、渡された引数を待ち行列（q）にためる。
          (window.clarity.q = window.clarity.q || []).push(arguments);
        // Clarityへの命令をためる関数の定義を終える。
        };
        // Clarity本体を読み込むためのscriptタグを作る。
        const clarityTag = document.createElement('script');
        // Clarity本体を非同期で取得する。実行時にはブラウザーの処理時間を使う。
        clarityTag.async = true;
        // Clarity本体の取得先URLを指定する。末尾はこのサイトのプロジェクトID。
        clarityTag.src = 'https://www.clarity.ms/tag/ye2j21dzip';
        // 作ったタグをhead内に追加し、Clarity本体の読み込みを開始する。
        document.head.appendChild(clarityTag);
      // 両方の解析を開始する処理（loadAnalytics）の定義を終える。
      };

      // 解析をすぐに始めず、少し待ってから開始するための処理をまとめる。
      const scheduleAnalytics = () => {
        // 指定した時間が経過したら、中の処理を実行するタイマーを用意する。
        window.setTimeout(() => {
          // ブラウザーが「空き時間に処理する機能」に対応しているか確認する。
          if ('requestIdleCallback' in window) {
            // 空き時間に解析を開始する。空き時間がなくても、1.5秒経過後には実行対象にする。
            window.requestIdleCallback(loadAnalytics, { timeout: 1500 });
          // 空き時間に処理する機能に対応していない場合は、こちらへ進む。
          } else {
            // 追加の空き時間待ちをせず、両方の解析を開始する。
            loadAnalytics();
          // ブラウザーの対応状況による分岐を終える。
          }
        // タイマーの待ち時間を2500ミリ秒（2.5秒）にする。
        }, 2500);
      // 開始を予約する処理（scheduleAnalytics）の定義を終える。
      };

      // ページの読み込みがすでに完了しているか確認する。
      if (document.readyState === 'complete') {
        // すでに読み込み済みなら、ここから2.5秒の待ち時間を開始する。
        scheduleAnalytics();
      // ページの読み込みがまだ完了していない場合は、こちらへ進む。
      } else {
        // 読み込み完了（load）を待って開始を予約する。once: trueで、この処理は一度だけにする。
        window.addEventListener('load', scheduleAnalytics, { once: true });
      // ページの読み込み状況による分岐を終える。
      }
    // 最初に作った関数を閉じ、末尾の()で実行する。
    })();
