const slideshow = document.querySelector('.gallery__slideshow');
  const slides = document.querySelectorAll('.gallery__slides .gallery__slide img');
  const prevButton = document.querySelector('.gallery__prev');
  const nextButton = document.querySelector('.gallery__next');
  const galleryContent = document.querySelector('.gallery__content');
  const galleryItems = document.querySelectorAll('.gallery__item');
  let currentSlide = 0;

  slides[currentSlide].style.display = 'block';
  galleryItems[currentSlide].classList.add('gallery__item--is-active');

  function prevSlide() {
    slides[currentSlide].style.display = 'none';
    galleryItems[currentSlide].classList.remove('gallery__item--is-active');
    currentSlide--;

    if (currentSlide < 0) {
      currentSlide = slides.length - 1;
    }

    slides[currentSlide].style.display = 'block';
    galleryItems[currentSlide].classList.add('gallery__item--is-active');
  }

  
  function nextSlide() {
    slides[currentSlide].style.display = 'none';
    galleryItems[currentSlide].classList.remove('gallery__item--is-active');
    currentSlide++;

    if (currentSlide >= slides.length) {
      currentSlide = 0;
    }

    slides[currentSlide].style.display = 'block';
    galleryItems[currentSlide].classList.add('gallery__item--is-active');
  }

  prevButton.addEventListener('click', prevSlide);
  nextButton.addEventListener('click', nextSlide);
  galleryContent.addEventListener('click', function(e) {
    const target = e.target;
    if (target.classList.contains('gallery__item-img')) {
      slides[currentSlide].style.display = 'none';
      galleryItems[currentSlide].classList.remove('gallery__item--is-active');
      currentSlide = [].indexOf.call(galleryItems, target.parentElement);
      slides[currentSlide].style.display = 'block';
      galleryItems[currentSlide].classList.add('gallery__item--is-active');
    }
  });
  
  