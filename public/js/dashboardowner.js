document.addEventListener('DOMContentLoaded', function () {
  var burger = document.getElementById('burger');
  if (!burger) return;
  burger.addEventListener('click', function () {
    if (document.body.classList.contains('sidebar-open')) {
      document.body.classList.remove('sidebar-open');
      document.body.classList.add('sidebar-collapsed');
      burger.setAttribute('aria-expanded', 'false');
    } else {
      document.body.classList.add('sidebar-open');
      document.body.classList.remove('sidebar-collapsed');
      burger.setAttribute('aria-expanded', 'true');
    }
  });
});


