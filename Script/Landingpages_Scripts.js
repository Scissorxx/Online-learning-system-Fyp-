let navbar = document.querySelector('.header .navbar');

document.querySelector('#menu-btn').onclick = () =>{
  navbar.classList.toggle('active');
  // loginForm.classList.remove('active');
}




window.onscroll = () =>{
  navbar.classList.remove('active');
}




   


