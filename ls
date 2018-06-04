[1mdiff --git a/index.php b/index.php[m
[1mindex 8023feb..c732ead 100644[m
[1m--- a/index.php[m
[1m+++ b/index.php[m
[36m@@ -27,7 +27,7 @@[m [minclude 'includes/formProcess.php';[m
         [m
         <h2>Login</h2>[m
         [m
[31m-        <!-- Main Navagation tabs, each containing their own logins -->[m
[32m+[m[32m        <!-- Main Navigation tabs, each containing their own logins -->[m[41m[m
         [m
         <!-- Login screen ready for backend php -->[m
 	<form action="includes/login.php" method="post">[m
[36m@@ -54,7 +54,8 @@[m [minclude 'includes/formProcess.php';[m
                 Use the second commented out button for when PHP is enabled.-->[m
                 <!--<a href="HTML/ProfessorActiveCases.php" class="btn btn-info" role="button" name"LoginSubmit">Submit</a>-->[m
                 <!--<button class="btn btn-info" type="submit" name="LoginSubmit">Submit</button>-->[m
[31m-               <input class="btn btn-info" type="submit" value="Submit" name="LoginSubmit">[m
[32m+[m[32m               <!--input class="btn btn-info" type="submit" value="Submit" name="LoginSubmit"-->[m[41m[m
[32m+[m			[32m   <button class="btn btn-info" type="submit" value="Submit" name="LoginSubmit">Submit</button>[m[41m[m
             </div>[m
         </form>[m
     </body>[m
