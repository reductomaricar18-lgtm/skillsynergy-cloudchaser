<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<h3>Exception Handling</h3>
<p>Java uses try-catch blocks to handle exceptions (errors at runtime). Example:</p>
<pre>
try {
  int x = 5 / 0;
} catch (ArithmeticException e) {
  System.out.println("Cannot divide by zero!");
}
</pre>
<script>
function loadJavaLessonTopic(topic, event) {
  if (event) event.stopPropagation();
  // ...rest of function...
}
if (typeof loadJavaLessonTopic !== 'undefined') window.loadJavaLessonTopic = loadJavaLessonTopic;
if (typeof loadJavaAssessment !== 'undefined') window.loadJavaAssessment = loadJavaAssessment;
</script> 