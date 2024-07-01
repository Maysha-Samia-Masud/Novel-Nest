function preventDefaultActivities(e)
{
    console.log("Suppressed default activity");
    e.preventDefault();
}
document.addEventListener('contextmenu', event => event.preventDefault());
//Bind key or key combination to preventDefaultActivities function.
$(document).ready(function(ev){
    if(Mousetrap)
    {
        Mousetrap.bind('command+c', preventDefaultActivities);
        Mousetrap.bind('command+v', preventDefaultActivities);
        Mousetrap.bind('ctrl+c', preventDefaultActivities);
        Mousetrap.bind('ctrl+v', preventDefaultActivities);
    }
});