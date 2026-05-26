
<dialog id="poweroff-dialog"class="dialog">
  <p>Really power off?

  &nbsp; &nbsp; &nbsp; &nbsp;
  <a href="commands.php?action=poweroff">
     <button commandfor="poweroff-dialog"
             command="close">Yes</button></a>

  &nbsp; &nbsp; &nbsp; &nbsp;
  <button commandfor="poweroff-dialog" command="close">No</button>
</dialog>

<hr>
<a href="index.php"><img src="images/browse.svg"
         width="48" height="48" alt="Browse"></a>

<button command="show-modal" commandfor="poweroff-dialog" class="farright">
    <img src="images/power.svg" width="48" height="48" alt="Power button">
</button>
