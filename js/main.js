/* Black Manor — main.js */

// ── Burger menu mobile ──
const burger = document.getElementById('burger');
const navLinks = document.getElementById('navLinks');

if (burger && navLinks) {
  burger.addEventListener('click', () => {
    navLinks.classList.toggle('open');
    burger.classList.toggle('active');
  });
}
document.querySelectorAll('.reviews-carousel').forEach(carousel => {
  const track = carousel.querySelector('.review-track');
  const prev = carousel.querySelector('.review-arrow.prev');
  const next = carousel.querySelector('.review-arrow.next');
  const scrollAmount = () => track.querySelector('.review-img').offsetWidth + 20;

  prev.addEventListener('click', () => track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }));
  next.addEventListener('click', () => track.scrollBy({ left: scrollAmount(), behavior: 'smooth' }));
});
// Dropdown mobile
document.querySelectorAll('.dropdown > a').forEach(link => {
  link.addEventListener('click', (e) => {
    if (window.innerWidth < 768) {
      e.preventDefault();
      link.closest('.dropdown').classList.toggle('open');
    }
  });
});

// Fermer le menu en cliquant un lien
document.querySelectorAll('.nav-links a:not(.dropdown > a)').forEach(link => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('open');
  });
});

// ── Scroll: header shadow ──
window.addEventListener('scroll', () => {
  const header = document.querySelector('.site-header');
  if (header) {
    header.style.boxShadow = window.scrollY > 30
      ? '0 4px 30px rgba(0,0,0,0.3)'
      : 'none';
  }
});


// ── Animation au scroll (Intersection Observer) ──
const animateOnScroll = (entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
      observer.unobserve(entry.target);
    }
  });
};

const observer = new IntersectionObserver(animateOnScroll, {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
});

document.querySelectorAll('.artist-card, .service-card, .shop-cat, .faq-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(el);
});
