<!-- Displaying the error message -->
<?php   if(isset($error) && !empty($error))
        {
?>
        <section class="error">
                <p><?=$error?></p>
        </section>
<?php   
        }

?>