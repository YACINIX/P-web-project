const reveals = document.querySelectorAll(".reveal");
if (reveals.length) {
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) e.target.classList.add("show");
      });
    },
    { threshold: 0.12 }
  );
  reveals.forEach((el) => io.observe(el));
}

const typingEl = document.querySelector(".typing");
if (typingEl) {
  let words = [];
  try {
    words = JSON.parse(typingEl.getAttribute("data-words") || "[]");
  } catch (e) {
    words = [];
  }

  if (Array.isArray(words) && words.length > 0) {
    let w = 0, i = 0, deleting = false;

    function tick() {
      const word = words[w] || "";
      typingEl.textContent = word.slice(0, i);

      if (!deleting) {
        i++;
        if (i > word.length) {
          deleting = true;
          setTimeout(tick, 800);
          return;
        }
      } else {
        i--;
        if (i < 0) {
          deleting = false;
          w = (w + 1) % words.length;
          i = 0;
        }
      }

      setTimeout(tick, deleting ? 35 : 55);
    }

    tick();
  }
}

const counters = document.querySelectorAll("[data-count]");
counters.forEach((el) => {
  const target = parseInt(el.getAttribute("data-count"), 10) || 0;

  const run = () => {
    const start = performance.now();
    const duration = 900;

    function step(t) {
      const p = Math.min(1, (t - start) / duration);
      const value = Math.round(target * p);
      el.textContent = value.toLocaleString();
      if (p < 1) requestAnimationFrame(step);
    }

    requestAnimationFrame(step);
  };

  const cIO = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          run();
          cIO.disconnect();
        }
      });
    },
    { threshold: 0.3 }
  );

  cIO.observe(el);
});

const recoGrid = document.querySelector(".reco-grid");
if (recoGrid) {
  const cards = Array.from(recoGrid.querySelectorAll(".reco-card"));
  const btnMore = document.querySelector("#reco-more");
  let shown = 8;

  function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
  }

  function render(limit) {
    recoGrid.innerHTML = "";
    cards.slice(0, limit).forEach((c) => recoGrid.appendChild(c));
  }

  shuffle(cards);
  render(shown);

  if (btnMore) {
    if (cards.length <= shown) btnMore.style.display = "none";
    btnMore.addEventListener("click", () => {
      shown = Math.min(cards.length, shown + 4);
      render(shown);
      if (shown >= cards.length) btnMore.style.display = "none";
    });
  }
}

const featuresData = [
  { title: "⚡ Rapide", text: "Pages légères, animations fluides, expérience simple." },
  { title: "🛒 Pratique", text: "Catalogue, filtres, panier prêt à l’emploi." },
  { title: "🔒 Sécurisé", text: "Sessions + password_hash + requêtes PDO préparées." },
  { title: "🎨 Design", text: "Landing clean, cards modernes, rendu pro." },
  { title: "📦 Produits", text: "Images uniformes, affichage stable, navigation rapide." }
];

const featureEls = document.querySelectorAll("[data-feature]");
if (featureEls.length) {
  const pick = () => featuresData[Math.floor(Math.random() * featuresData.length)];

  featureEls.forEach((el) => {
    const d = pick();
    el.innerHTML = `<h3>${d.title}</h3><p>${d.text}</p>`;
  });

  setInterval(() => {
    featureEls.forEach((el) => {
      const d = pick();
      el.innerHTML = `<h3>${d.title}</h3><p>${d.text}</p>`;
    });
  }, 4500);
}
