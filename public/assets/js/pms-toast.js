// Auto-dismiss toasts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.pms-toast').forEach(function(toast) {
    setTimeout(function() {
      toast.style.transition = 'opacity .5s';
      toast.style.opacity = '0';
      setTimeout(function() { toast.remove(); }, 500);
    }, 5000);
  });
});
