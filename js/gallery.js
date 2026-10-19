document.addEventListener("DOMContentLoaded", () => {
  const gallery = document.getElementById("gallery");

  /* const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightbox-img");
*/
  const path = gallery.dataset.path;
  const max = 30;

  // ── GENERATION GALERIE ──
  for (let i = 1; i <= max; i++) {
    const num = String(i).padStart(2, "0");
    const src = `${path}img_${num}.jpeg`;

    const img = document.createElement("img");
    img.src = src;

    // supprime si image inexistante
    img.onerror = () => {
      img.remove();
    };
    /*
    // ouverture lightbox
    img.addEventListener("click", () => {
      lightboxImg.src = src;
      lightbox.classList.add("active");
    });
*/
    gallery.appendChild(img);
  }

  // ── FERMETURE LIGHTBOX ──
  /* lightbox.addEventListener("click", () => {
    lightbox.classList.remove("active");
    lightboxImg.src = "";
  });*/
});
