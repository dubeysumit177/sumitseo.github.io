"use strict";

$("#swal-1").on('click',function() {
	swal('Hello');
});

$("#swal-2").on('click',function() {
	swal('Good Job', 'You clicked the button!', 'success');
});

$("#swal-3").on('click',function() {
	swal('Good Job', 'You clicked the button!', 'warning');
});

$("#swal-4").on('click',function() {
	swal('Good Job', 'You clicked the button!', 'info');
});

$("#swal-5").on('click',function() {
	swal('Good Job', 'You clicked the button!', 'error');
});

$("#swal-6").on('click',function() {
  swal({
      title: 'Are you sure?',
      text: 'Once deleted, you will not be able to recover this imaginary file!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
      swal('Poof! Your imaginary file has been deleted!', {
        icon: 'success',
      });
      } else {
      swal('Your imaginary file is safe!');
      }
    });
});

$("#swal-7").on('click',function() {
  swal({
    title: 'What is your name?',
    content: {
    element: 'input',
    attributes: {
      placeholder: 'Type your name',
      type: 'text',
    },
    },
  }).then((data) => {
    swal('Hello, ' + data + '!');
  });
});

$("#swal-8").on('click',function() {
  swal('This modal will disappear soon!', {
    buttons: false,
    timer: 3000,
  });
});