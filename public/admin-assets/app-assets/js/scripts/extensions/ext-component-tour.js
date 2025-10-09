/*=========================================================================================
	File Name: tour.js
	Description: tour
	----------------------------------------------------------------------------------------
	Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: Pixinvent
	Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

  var startBtn = $('#tour');
  function setupTour(tour) {
    var backBtnClass = 'btn btn-sm btn-outline-primary',
      nextBtnClass = 'btn btn-sm btn-primary btn-next'; 

    if(tour.addStep({
      title: 'Update Setting Information',
      text: 'You need to add all information', 
      attachTo: { element: '#dropdown-user', on: 'bottom' },
      buttons: [
        {
          action: tour.cancel,
          classes: backBtnClass,
          text: 'Skip'
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          id : 'next_btn_id',
          action: tour.next
        }
      ] 
    })){ 
      $('.main-menu.menu-fixed').animate({scrollTop:300}, '500');  
    }

    if(tour.addStep({
      title: 'Software Setting',
      text: 'Update Software Setting',
      attachTo: { element: '#software_setting', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ] 
    })){ 
      $("#software_setting").closest('li').addClass('active');  
    } 
    
    tour.addStep({
      title: 'SMTP Setup',
      text: 'Setup your all smtp information',
      attachTo: { element: '#smtp_setup', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    tour.addStep({
      title: 'Company Setup',
      text: 'Setup your company information',
      attachTo: { element: '#company_setup', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    tour.addStep({
      title: 'API Configuration',
      text: 'api configuration add',
      attachTo: { element: '#api_configuration', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    tour.addStep({
      title: 'Add Cryptop Address',
      text: 'Add new crypto address',
      attachTo: { element: '#crypto_deposit_setting', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    tour.addStep({
      title: 'Currency Pair',
      text: 'Add your currency pair',
      attachTo: { element: '#currency-pair', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    tour.addStep({
      title: 'Ib Settings',
      text: 'Add New Ib',
      attachTo: { element: '#ib_setting', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    tour.addStep({
      title: 'Trader Settings',
      text: 'Add New Trader on trader settings',
      attachTo: { element: '#trader_setting', on: 'left' },
      buttons: [
        {
          text: 'Skip',
          classes: backBtnClass,
          action: tour.cancel
        },
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Next',
          classes: nextBtnClass,
          action: tour.next
        }
      ]
    });
    
    tour.addStep({
      title: 'Finance Setting',
      text: 'Finance Setting Add',
      attachTo: { element: '#finance_setting', on: 'left' },
      buttons: [
        {
          text: 'Back',
          classes: backBtnClass,
          action: tour.back
        },
        {
          text: 'Finish',
          classes: nextBtnClass,
          action: tour.cancel
        }
      ]
    });

    return tour;
  }

  if (startBtn.length) {
    startBtn.on('click', function () {
      var tourVar = new Shepherd.Tour({
        defaultStepOptions: {
          classes: 'shadow-md bg-purple-dark',
          cancelIcon: {
            enabled: true
          },
          scrollTo: { behavior: 'smooth', block: 'center' }
        },
        useModalOverlay: true,
        
      });

      
      setupTour(tourVar).start();
    });
  }
});
