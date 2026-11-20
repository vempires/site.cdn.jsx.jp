
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('.menu-btn');
  const nav = document.querySelector('.nav');
  if (btn && nav) btn.addEventListener('click', () => nav.classList.toggle('open'));

  const search = document.querySelector('#sportSearch');
  if (search) {
    const cards = [...document.querySelectorAll('.sport-card')];
    search.addEventListener('input', () => {
      const q = search.value.trim().toLowerCase();
      cards.forEach(card => {
        card.style.display = card.dataset.search.includes(q) ? '' : 'none';
      });
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  function openPlayerGate(player) {
    if (!player) return;
    var gate = player.querySelector("[data-player-gate]");
    if (!gate) return;

    gate.hidden = false;
    player.classList.add("gate-open");
    gate.setAttribute("aria-hidden", "false");

    var action = gate.querySelector("a.btn");
    if (action) window.setTimeout(function(){ action.focus(); }, 60);
  }

  document.querySelectorAll("[data-premium-player]").forEach(function (player) {
    var trigger = player.querySelector("[data-player-open]");
    if (!trigger) return;
    trigger.addEventListener("click", function () {
      openPlayerGate(player);
    });
  });

});

(function () {
  function syncOne(card) {
    if (!card || !window.matchMedia("(min-width: 981px)").matches) return;

    var media = card.querySelector(".compact-player-media");
    var side = card.querySelector("[data-desktop-side-panel]");
    if (!media || !side) return;

    var h = Math.round(media.getBoundingClientRect().height);
    if (h < 200) return;

    side.style.setProperty("height", h + "px", "important");
    side.style.setProperty("max-height", h + "px", "important");

    var quick = side.querySelector("[data-desktop-quick]");
    if (quick) {
      quick.style.setProperty("height", h + "px", "important");
      quick.style.setProperty("max-height", h + "px", "important");
    }

  }

  function syncAll() {
    document.querySelectorAll(".compact-watch-card").forEach(syncOne);
  }

  document.addEventListener("DOMContentLoaded", function () {
    syncAll();

    // Run again after images/fonts/layout settle.
    window.requestAnimationFrame(syncAll);
    window.setTimeout(syncAll, 120);
    window.setTimeout(syncAll, 500);

    document.querySelectorAll(".compact-player-media").forEach(function (media) {
      if ("ResizeObserver" in window) {
        var ro = new ResizeObserver(function () {
          syncOne(media.closest(".compact-watch-card"));
        });
        ro.observe(media);
      }

      var img = media.querySelector("img");
      if (img && !img.complete) {
        img.addEventListener("load", function () {
          syncOne(media.closest(".compact-watch-card"));
        }, {once:true});
      }
    });

    var resizeTimer = null;
    window.addEventListener("resize", function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(syncAll, 80);
    }, {passive:true});
  });
})();
