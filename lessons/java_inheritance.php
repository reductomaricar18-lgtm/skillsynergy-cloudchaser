<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<h3>Inheritance and Polymorphism</h3>
<p>Inheritance allows a class to inherit fields and methods from another class. Polymorphism lets you use a unified interface for different data types.</p>
<pre>
class Animal {
  void sound() { System.out.println("Some sound"); }
}
class Dog extends Animal {
  void sound() { System.out.println("Bark"); }
}
</pre>
<script>
if (typeof loadJavaLessonTopic !== 'undefined') window.loadJavaLessonTopic = loadJavaLessonTopic;
if (typeof loadJavaAssessment !== 'undefined') window.loadJavaAssessment = loadJavaAssessment;
</script> 