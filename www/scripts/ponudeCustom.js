
$(document).ready(function()
{
	"use strict";

	/* 

	1. Vars and Inits

	*/

	var header = $('.home');
	var searchActive = false;

	setHeader();
	
	$(window).on('resize', function()
	{
		setHeader();
	});

	$(document).on('scroll', function()
	{
		setHeader();
	});

	/* 

	2. Set Header

	*/

	function setHeader()
	{
		if(window.innerWidth < 992)
		{
			if($(window).scrollTop() > 100)
			{
				header.addClass('scrolled');
			}
			else
			{
				header.removeClass('scrolled');
			}
		}
		else
		{
			if($(window).scrollTop() > 100)
			{
				header.addClass('scrolled');
			}
			else
			{
				header.removeClass('scrolled');
			}
		}
		if(window.innerWidth > 991 && menuActive)
		{
			closeMenu();
		}
	}
});


var currentPage = 1;
var totalPages = 20;

document.querySelectorAll('.paginationjs-page').forEach(function(page) {
  page.addEventListener('click', function() {
    currentPage = parseInt(this.dataset.num);
    updatePagination();
  });
});

document.querySelector('.paginationjs-prev').addEventListener('click', function() {
  if (currentPage > 1) {
    currentPage--;
    updatePagination();
  }
});

document.querySelector('.paginationjs-next').addEventListener('click', function() {
  if (currentPage < totalPages) {
    currentPage++;
    updatePagination();
  }
});

function updatePagination() {
  document.querySelectorAll('.paginationjs-page').forEach(function(page) {
    page.classList.remove('active');
    if (page.dataset.num == currentPage) {
      page.classList.add('active');
    }
  });

  var prevButton = document.querySelector('.paginationjs-prev');
  if (currentPage <= 1) {
    prevButton.classList.add('disabled');
  } else {
    prevButton.classList.remove('disabled');
  }

  var nextButton = document.querySelector('.paginationjs-next');
  if (currentPage >= totalPages) {
    nextButton.classList.add('disabled');
  } else {
    nextButton.classList.remove('disabled');
  }
}


$(document).ready(function() {
	$('.pagination-listing').click(function() {
	  $('.pagination-listing').removeClass('active');
	  $(this).addClass('active');
	});
  });

$(document).ready(function() {
	$('.pagination-listing.50').addClass('active');
  
	$('.pagination-listing').click(function() {
	  $('.pagination-listing').removeClass('active');
	  $(this).addClass('active');
	});
  });
  
  