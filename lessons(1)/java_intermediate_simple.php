<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<h2>Java Intermediate Lesson</h2>
<p>Welcome! Click a topic to read:</p>
<div id="javaTopics">
  <button type="button" onclick="loadJavaLessonTopic('oop', event)">Object-Oriented Programming (OOP) in Java</button>
  <button type="button" onclick="loadJavaLessonTopic('inheritance', event)">Inheritance and Polymorphism</button>
  <button type="button" onclick="loadJavaLessonTopic('exception', event)">Exception Handling</button>
  <button type="button" onclick="loadJavaLessonTopic('collections', event)">Collections Framework</button>
  <button type="button" onclick="loadJavaLessonTopic('fileio', event)">File I/O</button>
</div>
<div id="javaTopicContent" style="margin-top:20px;"></div>
<script>
const javaTopics = ['oop', 'inheritance', 'exception', 'collections', 'fileio'];
let currentTopicIndex = null;

function loadJavaLessonTopic(topic, event) {
  if (event) event.stopPropagation();
  console.log('Topic button clicked:', topic);
  currentTopicIndex = javaTopics.indexOf(topic);
  
  const topicContent = {
    oop: '<h3>Object-Oriented Programming (OOP) in Java</h3><p>OOP is a programming paradigm based on objects and classes. In Java, everything is an object. Key OOP principles are:</p><ul><li><b>Encapsulation</b>: Bundling data and methods together.</li><li><b>Abstraction</b>: Hiding complex details and showing only essentials.</li><li><b>Inheritance</b>: Acquiring properties from another class.</li><li><b>Polymorphism</b>: One interface, many implementations.</li></ul>',
    inheritance: '<h3>Inheritance and Polymorphism</h3><p>Inheritance allows a class to inherit fields and methods from another class. Polymorphism lets you use a unified interface for different data types.</p><pre>class Animal { void sound() { System.out.println("Some sound"); } } class Dog extends Animal { void sound() { System.out.println("Bark"); } }</pre>',
    exception: '<h3>Exception Handling</h3><p>Java uses try-catch blocks to handle exceptions (errors at runtime). Example:</p><pre>try { int x = 5 / 0; } catch (ArithmeticException e) { System.out.println("Cannot divide by zero!"); }</pre>',
    collections: '<h3>Collections Framework</h3><p>The Java Collections Framework provides data structures like List, Set, and Map.</p><pre>import java.util.*; List<String> names = new ArrayList<>(); names.add("Alice"); names.add("Bob");</pre>',
    fileio: '<h3>File I/O</h3><p>Java can read and write files using classes like FileReader and FileWriter.</p><pre>import java.io.*; FileWriter writer = new FileWriter("output.txt"); writer.write("Hello, file!"); writer.close();</pre>'
  };
  
  const contentDiv = document.getElementById('javaTopicContent');
  console.log('Content div found:', contentDiv);
  
  if (!contentDiv) {
    console.error('javaTopicContent div not found!');
    return;
  }
  
  const content = topicContent[topic];
  console.log('Content for topic:', content.substring(0, 100));
  
  let nav = '';
  if (currentTopicIndex > 0) {
    nav += `<button onclick="loadJavaLessonTopic('${javaTopics[currentTopicIndex-1]}', event)" style="margin:5px; padding:8px; background:#52c41a; color:white; border:none; border-radius:3px; cursor:pointer;">Previous Topic</button> `;
  }
  if (currentTopicIndex < javaTopics.length - 1) {
    nav += `<button onclick="loadJavaLessonTopic('${javaTopics[currentTopicIndex+1]}', event)" style="margin:5px; padding:8px; background:#52c41a; color:white; border:none; border-radius:3px; cursor:pointer;">Next Topic</button>`;
  } else {
    nav += `<button onclick="loadJavaAssessment(event)" style="margin:5px; padding:8px; background:#fa8c16; color:white; border:none; border-radius:3px; cursor:pointer;">Proceed to Assessment</button>`;
  }
  
  const finalHtml = content + '<div style="margin-top:20px;">' + nav + '</div>';
  console.log('Final HTML to insert:', finalHtml.substring(0, 200));
  contentDiv.innerHTML = finalHtml;
  console.log('Content div updated successfully');
}

function loadJavaAssessment(event) {
  if (event) event.stopPropagation();
  const contentDiv = document.getElementById('javaTopicContent');
  if (!contentDiv) {
    console.error('javaTopicContent div not found!');
    return;
  }
  
  const assessmentContent = '<h3>Java Intermediate Assessment</h3><ol><li>What is inheritance in Java?</li><li>Write a try-catch block that handles division by zero.</li><li>How do you add an item to an ArrayList?</li><li>What is the purpose of the Collections Framework?</li></ol><p><b>Submit your answers to your tutor or discuss them in the chat!</b></p>';
  
  console.log('Loading assessment content');
  contentDiv.innerHTML = assessmentContent;
  console.log('Assessment content loaded successfully');
}
window.loadJavaLessonTopic = loadJavaLessonTopic;
window.loadJavaAssessment = loadJavaAssessment;
</script> 