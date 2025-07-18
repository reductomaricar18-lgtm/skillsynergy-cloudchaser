<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<h3>File I/O</h3>
<p>Java can read and write files using classes like FileReader and FileWriter.</p>
<pre>
import java.io.*;
FileWriter writer = new FileWriter("output.txt");
writer.write("Hello, file!");
writer.close();
</pre>
<script>
if (typeof loadJavaLessonTopic !== 'undefined') window.loadJavaLessonTopic = loadJavaLessonTopic;
if (typeof loadJavaAssessment !== 'undefined') window.loadJavaAssessment = loadJavaAssessment;
</script> 