// ヘッダーのメニューボタンを取得する
const headerToggle = document.querySelector(".header__toggle");

// ヘッダーのナビメニューを取得する
const headerMenu = document.querySelector("#header-menu");

// ヘッダーのロゴを取得する
const headerLogo = document.querySelector(".header__logo");

// 画面幅が1024px以上かどうかを判定する
const desktopQuery = window.matchMedia("(min-width: 1024px)");

// メニュー背後のコンテンツを取得する
const pageContents = document.querySelectorAll("main, footer");

// スマホ用ドロワーメニューを開く
const openDrawer = () => {
  // PC幅の場合は処理を止める
  if (desktopQuery.matches) return;

  // bodyにドロワー開閉用のクラスを付ける
  document.body.classList.add("is-drawer-open");

  // 開いたら背後を操作不可にする
  pageContents.forEach((content) => {
    content.inert = true;
  });

  // メニューボタンを「開いている状態」にする
  headerToggle?.setAttribute("aria-expanded", "true");

  // 読み上げ用の説明を「メニューを閉じる」に変える
  headerToggle?.setAttribute("aria-label", "メニューを閉じる");
};

// スマホ用ドロワーメニューを閉じる
const closeDrawer = () => {
  // bodyからドロワー開閉用のクラスを外す
  document.body.classList.remove("is-drawer-open");

  // 閉じたら背後を操作可能に戻す
  pageContents.forEach((content) => {
    content.inert = false;
  });

  // メニューボタンを「閉じている状態」にする
  headerToggle?.setAttribute("aria-expanded", "false");

  // 読み上げ用の説明を「メニューを開く」に変える
  headerToggle?.setAttribute("aria-label", "メニューを開く");
};

// 画面幅が変わった時の処理を設定する
desktopQuery.addEventListener("change", (event) => {
  // PC幅になった場合
  if (event.matches) {
    // ドロワーを閉じる
    closeDrawer();
  }
});




// 初回表示がPC幅の場合
if (desktopQuery.matches) {
  // ドロワーを閉じた状態にする
  closeDrawer();
}

// メニューボタンをクリックした時の処理を設定する
headerToggle?.addEventListener("click", () => {
  // すでにドロワーが開いている場合
  if (document.body.classList.contains("is-drawer-open")) {
    // ドロワーを閉じる
    closeDrawer();

    // ここで処理を終わる
    return;
  }

  // ドロワーが閉じている場合は開く
  openDrawer();
});

// ヘッダーメニュー内のリンクをすべて取得して処理する
headerMenu?.querySelectorAll("a").forEach((link) => {
  // ナビリンクをクリックした時
  link.addEventListener("click", closeDrawer);
});

// ロゴをクリックした時の処理を設定する
headerLogo?.addEventListener("click", closeDrawer);






// キーボードが押されたときに処理する
document.addEventListener("keydown", (event) => {

  // 押されたキーがEscキーか確認する
  if (
    event.key === "Escape" &&

    // メニューが開いているか確認する
    document.body.classList.contains("is-drawer-open")
  ) {

    // メニューを閉じる
    closeDrawer();

    // 操作位置をメニューボタンへ戻す
    headerToggle?.focus();
  }
});






// FAQ項目をすべて取得する
const faqItems = document.querySelectorAll(".faq__item");

// ユーザーが「動きを減らす設定」にしているか確認する
const reduceMotionQuery = window.matchMedia("(prefers-reduced-motion: reduce)");

// FAQ開閉アニメーションで一時的に付けたstyleを消す
const clearFaqAnimationStyle = (item) => {
  // heightの直接指定を消す
  item.style.height = "";

  // overflowの直接指定を消す
  item.style.overflow = "";
};

// FAQを閉じる
const closeFaqItem = (item) => {
  // クリックされたFAQの質問部分を取得する
  const summary = item.querySelector(".faq__question");

  // 質問部分がない、または開いていない場合は処理を止める
  if (!summary || !item.open) return;

  // 連続クリック時に前のアニメーションを止める
  item.faqAnimation?.cancel();

  // 動きを減らす設定の場合はアニメーションさせない
  if (reduceMotionQuery.matches) {
    // FAQを閉じる
    item.open = false;

    // 一時的に付けたstyleを消す
    clearFaqAnimationStyle(item);

    // ここで処理を終わる
    return;
  }

  // 現在のFAQ全体の高さを取得する
  const startHeight = item.offsetHeight;

  // 質問部分だけの高さを取得する
  const endHeight = summary.offsetHeight;

  // はみ出した中身を隠す
  item.style.overflow = "hidden";

  // FAQの高さを、現在の高さから質問部分の高さまで縮める
  item.faqAnimation = item.animate(
    // heightをstartHeightからendHeightへ変化させる
    { height: [`${startHeight}px`, `${endHeight}px`] },

    // アニメーション時間と動き方を指定する
    { duration: 260, easing: "ease" }
  );

  // アニメーションが終わった後の処理
  item.faqAnimation.onfinish = () => {
    // FAQを閉じた状態にする
    item.open = false;

    // 一時的に付けたstyleを消す
    clearFaqAnimationStyle(item);
  };
};

// FAQを開く
const openFaqItem = (item) => {
  // クリックされたFAQの質問部分を取得する
  const summary = item.querySelector(".faq__question");

  // 質問部分がない、またはすでに開いている場合は処理を止める
  if (!summary || item.open) return;

  // 連続クリック時に前のアニメーションを止める
  item.faqAnimation?.cancel();

  // 動きを減らす設定の場合はアニメーションさせない
  if (reduceMotionQuery.matches) {
    // FAQを開く
    item.open = true;

    // 一時的に付けたstyleを消す
    clearFaqAnimationStyle(item);

    // ここで処理を終わる
    return;
  }

  // 質問部分だけの高さを取得する
  const startHeight = summary.offsetHeight;

  // 最初は質問部分だけの高さに固定する
  item.style.height = `${startHeight}px`;

  // はみ出した中身を隠す
  item.style.overflow = "hidden";

  // FAQを開いた状態にする
  item.open = true;

  // 回答を含めたFAQ全体の高さを取得する
  const endHeight = item.scrollHeight;

  // FAQの高さを、質問部分の高さから全体の高さまで広げる
  item.faqAnimation = item.animate(
    // heightをstartHeightからendHeightへ変化させる
    { height: [`${startHeight}px`, `${endHeight}px`] },

    // アニメーション時間と動き方を指定する
    { duration: 260, easing: "ease" }
  );

  // アニメーションが終わった後の処理
  item.faqAnimation.onfinish = () => {
    // 一時的に付けたstyleを消す
    clearFaqAnimationStyle(item);
  };
};

// クリックされたFAQを開閉する（複数同時に開ける）
faqItems.forEach((item) => {
  // 各FAQの質問部分を取得する
  const summary = item.querySelector(".faq__question");

  // 質問部分がクリックされた時の処理を設定する
  summary?.addEventListener("click", (event) => {
    // details / summary の標準開閉を止める
    event.preventDefault();

    // すでに開いている場合
    if (item.open) {
      // FAQを閉じる
      closeFaqItem(item);

      // ここで処理を終わる
      return;
    }

    // 閉じている場合はFAQを開く
    openFaqItem(item);
  });
});
