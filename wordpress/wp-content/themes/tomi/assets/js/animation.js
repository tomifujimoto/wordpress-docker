// ユーザーが「動きを減らす」設定を有効にしているか確認する
const reduceAnimationQuery = window.matchMedia("(prefers-reduced-motion: reduce)");

// スクロール時に表示する要素のCSSセレクターをまとめる
  const revealTargets = document.querySelectorAll(".js-reveal");

// 時間差で表示するカード類のセレクターをまとめる
const staggerSelectors = [
  ".about__reason-card",
  ".about__skill-card",
  ".works__link",
  ".service__card",
  ".price__card",
  ".process__card",
  ".faq__item",
];

// カードの種類ごとに表示タイミングを設定する
staggerSelectors.forEach((selector) => {
  document.querySelectorAll(selector).forEach((element, index) => {
    element.style.setProperty("--reveal-delay", `${Math.min(index * 100, 300)}ms`);
  });
});

// 動きを減らす設定、または画面監視機能が使えない場合はすぐに表示する
if (reduceAnimationQuery.matches || !("IntersectionObserver" in window)) {
  revealTargets.forEach((element) => element.classList.add("is-visible"));
} else {
  const revealObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      });
    },
    {
      threshold: 0.12,
      rootMargin: "0px 0px -8% 0px",
    }
  );

  revealTargets.forEach((element) => revealObserver.observe(element));
}

// ここまで動いたら、index.html側の安全網が発動しないように準備完了を知らせる
document.documentElement.classList.add("js-animation-ready");
