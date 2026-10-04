// JSが使える時だけ、スクロール表示用のCSSを有効にする
    document.documentElement.classList.add("js-enabled");

    // animation.jsが動かなかった時は、隠した要素が見えないままにならないようにする
    window.addEventListener("load", () => {
      setTimeout(() => {
        if (!document.documentElement.classList.contains("js-animation-ready")) {
          document.documentElement.classList.remove("js-enabled");
        }
      }, 3000);
    });
