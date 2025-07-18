<?php
// If you want session protection, uncomment below:
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Basics - SkillSynergy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #007bff; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #0056b3; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #f0f4ff; color: #4a63ff; }
        .code-block { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .tip-box { background: #fef5e7; border: 1px solid #f6ad55; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #c05621; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #2d3748; }
        .highlight { background: #fef5e7; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to CSS</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>CSS (Cascading Style Sheets) is a stylesheet language used to describe the presentation of a document written in HTML or XML. It is one of the three core technologies of the World Wide Web, along with HTML and JavaScript.</p>
          <p>CSS is designed to separate content from presentation, making it easier to manage and update the look of a website. It consists of a set of rules that specify how HTML elements should be displayed on screen, paper, or other media.</p>
          <ul>
            <li><strong>Selectors:</strong> Patterns used to select and style HTML elements.</li>
            <li><strong>Properties:</strong> Specific characteristics of elements, such as color, font, size, and position.</li>
            <li><strong>Values:</strong> The actual values assigned to properties, like "red", "#ff0000", or "16px".</li>
          </ul>
          <p>CSS is often referred to as "Cascading" because styles cascade down from the most specific rule to the most general one. This means that if multiple rules apply to the same element, the most specific rule takes precedence.</p>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>CSS Selectors</h2>
          <p>CSS selectors are patterns used to select and style HTML elements.</p>
          <div class="code-block">
/* Select all paragraphs */
p {
    color: blue;
}

/* Select all headings */
h1, h2, h3 {
    font-weight: bold;
}

/* Select all elements */
* {
    margin: 0;
    padding: 0;
}
          </div>
          <div class="code-block">
/* Class selector */
.highlight {
    background-color: yellow;
}

/* ID selector */
#header {
    background-color: #333;
    color: white;
}

/* Multiple classes */
.button.primary {
    background-color: blue;
    color: white;
}
          </div>
          <div class="code-block">
/* Descendant selector - any nested element */
div p {
    margin: 10px;
}

/* Child selector - direct children only */
div > p {
    padding: 5px;
}

/* Adjacent sibling selector */
h1 + p {
    font-size: 18px;
}
          </div>
          <div class="code-block">
/* Pseudo-classes */
a:hover {
    color: red;
}

button:active {
    background-color: darkblue;
}

input:focus {
    border: 2px solid blue;
}

/* Pseudo-elements */
p::first-letter {
    font-size: 24px;
    font-weight: bold;
}

p::after {
    content: " - End of paragraph";
    color: gray;
}
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Colors & Backgrounds</h2>
          <p>CSS provides various ways to specify colors and background properties.</p>
          <div class="code-block">
/* Named colors */
color: red;
color: blue;
color: green;

/* Hexadecimal colors */
color: #ff0000;  /* red */
color: #00ff00;  /* green */
color: #0000ff;  /* blue */

/* RGB values */
color: rgb(255, 0, 0);      /* red */
color: rgb(0, 255, 0);      /* green */
color: rgb(0, 0, 255);      /* blue */

/* RGBA with transparency */
color: rgba(255, 0, 0, 0.5);  /* semi-transparent red */

/* HSL values */
color: hsl(0, 100%, 50%);   /* red */
color: hsl(120, 100%, 50%); /* green */
          </div>
          <div class="code-block">
/* Background color */
background-color: #f0f0f0;

/* Background image */
background-image: url('image.jpg');

/* Background repeat */
background-repeat: no-repeat;
background-repeat: repeat-x;
background-repeat: repeat-y;

/* Background position */
background-position: center;
background-position: top left;
background-position: 50% 50%;

/* Background size */
background-size: cover;
background-size: contain;
background-size: 100% 100%;
          </div>
          <div class="code-block">
/* All background properties in one line */
background: #f0f0f0 url('image.jpg') no-repeat center/cover;

/* Breakdown:
   background-color: #f0f0f0
   background-image: url('image.jpg')
   background-repeat: no-repeat
   background-position: center
   background-size: cover
*/
          </div>
          <div class="code-block">
/* Linear gradient */
background: linear-gradient(to right, red, blue);
background: linear-gradient(45deg, red, yellow, green);

/* Radial gradient */
background: radial-gradient(circle, red, blue);
background: radial-gradient(ellipse at center, red, blue);

/* Multiple gradients */
background: 
    linear-gradient(to right, red, transparent),
    url('image.jpg');
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Text Styling</h2>
          <p>CSS provides extensive control over text appearance and formatting.</p>
          <div class="code-block">
/* Font family */
font-family: Arial, sans-serif;
font-family: "Times New Roman", serif;
font-family: 'Courier New', monospace;

/* Font size */
font-size: 16px;
font-size: 1.2em;
font-size: 120%;

/* Font weight */
font-weight: normal;
font-weight: bold;
font-weight: 400;
font-weight: 700;

/* Font style */
font-style: normal;
font-style: italic;
font-style: oblique;
          </div>
          <div class="code-block">
/* Text color */
color: #333333;

/* Text alignment */
text-align: left;
text-align: center;
text-align: right;
text-align: justify;

/* Text decoration */
text-decoration: none;
text-decoration: underline;
text-decoration: line-through;
text-decoration: overline;

/* Text transform */
text-transform: none;
text-transform: uppercase;
text-transform: lowercase;
text-transform: capitalize;
          </div>
          <div class="code-block">
/* Line height */
line-height: 1.5;
line-height: 24px;
line-height: 150%;

/* Letter spacing */
letter-spacing: 2px;
letter-spacing: -1px;

/* Word spacing */
word-spacing: 5px;

/* Text indent */
text-indent: 20px;
text-indent: 2em;
          </div>
          <div class="code-block">
/* Basic text shadow */
text-shadow: 2px 2px 4px rgba(0,0,0,0.5);

/* Multiple shadows */
text-shadow: 
    2px 2px 4px rgba(0,0,0,0.5),
    0 0 10px rgba(255,255,255,0.3);

/* Glow effect */
text-shadow: 0 0 10px #ff0000;

/* 3D effect */
text-shadow: 
    1px 1px 0 #ccc,
    2px 2px 0 #bbb,
    3px 3px 0 #aaa;
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Box Model</h2>
          <p>The CSS box model describes how elements are sized and spaced.</p>
          <div class="code-block">
/* Content - the actual content of the element */
width: 200px;
height: 100px;

/* Padding - space inside the element */
padding: 10px;
padding: 10px 20px;  /* top/bottom, left/right */
padding: 10px 20px 15px 25px;  /* top, right, bottom, left */

/* Border - line around the element */
border: 1px solid black;
border-width: 2px;
border-style: solid;
border-color: red;

/* Margin - space outside the element */
margin: 10px;
margin: 10px 20px;
margin: 10px 20px 15px 25px;
          </div>
          <div class="code-block">
/* Content box (default) */
box-sizing: content-box;
/* Width includes only content, not padding/border */

/* Border box */
box-sizing: border-box;
/* Width includes content, padding, and border */

/* Universal box-sizing */
* {
    box-sizing: border-box;
}
          </div>
          <div class="code-block">
/* Border styles */
border-style: solid;
border-style: dashed;
border-style: dotted;
border-style: double;
border-style: groove;
border-style: ridge;
border-style: inset;
border-style: outset;

/* Individual borders */
border-top: 2px solid red;
border-right: 1px dashed blue;
border-bottom: 3px dotted green;
border-left: 1px solid black;

/* Border radius */
border-radius: 5px;
border-radius: 50%;  /* creates a circle */
border-radius: 10px 20px 30px 40px;  /* top-left, top-right, bottom-right, bottom-left */
          </div>
          <div class="code-block">
/* Margin shorthand */
margin: 10px;           /* all sides */
margin: 10px 20px;      /* top/bottom, left/right */
margin: 10px 20px 15px; /* top, left/right, bottom */
margin: 10px 20px 15px 25px; /* top, right, bottom, left */

/* Padding shorthand */
padding: 10px;          /* all sides */
padding: 10px 20px;     /* top/bottom, left/right */
padding: 10px 20px 15px; /* top, left/right, bottom */
padding: 10px 20px 15px 25px; /* top, right, bottom, left */
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Layout & Display</h2>
          <p>CSS provides various ways to control element layout and positioning.</p>
          <div class="code-block">
/* Block elements */
display: block;
/* Takes full width, starts on new line */

/* Inline elements */
display: inline;
/* Takes only necessary width, no line breaks */

/* Inline-block */
display: inline-block;
/* Like inline but respects width/height */

/* Flexbox */
display: flex;
/* Creates a flex container */

/* Grid */
display: grid;
/* Creates a grid container */

/* None */
display: none;
/* Hides the element completely */
          </div>
          <div class="code-block">
/* Static (default) */
position: static;

/* Relative */
position: relative;
top: 10px;
left: 20px;

/* Absolute */
position: absolute;
top: 0;
right: 0;

/* Fixed */
position: fixed;
top: 0;
left: 0;

/* Sticky */
position: sticky;
top: 0;
          </div>
          <div class="code-block">
/* Flex container */
.container {
    display: flex;
    justify-content: center;    /* horizontal alignment */
    align-items: center;        /* vertical alignment */
    flex-direction: row;        /* or column */
    flex-wrap: wrap;           /* wrap items */
}

/* Flex items */
.item {
    flex: 1;                   /* grow and shrink equally */
    flex-grow: 1;              /* grow factor */
    flex-shrink: 1;            /* shrink factor */
    flex-basis: 200px;         /* initial size */
}
          </div>
          <div class="code-block">
/* Float */
float: left;
float: right;
float: none;

/* Clear */
clear: left;
clear: right;
clear: both;
clear: none;

/* Example */
.image {
    float: left;
    margin-right: 10px;
}

.text {
    clear: left;
}
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Responsive Design</h2>
          <p>Responsive design ensures websites work well on all device sizes.</p>
          <div class="code-block">
/* Mobile first approach */
.container {
    width: 100%;
    padding: 10px;
}

/* Tablet and up */
@media (min-width: 768px) {
    .container {
        width: 750px;
        margin: 0 auto;
    }
}

/* Desktop and up */
@media (min-width: 1024px) {
    .container {
        width: 970px;
    }
}

/* Large desktop */
@media (min-width: 1200px) {
    .container {
        width: 1170px;
    }
}
          </div>
          <div class="code-block">
/* Responsive images */
img {
    max-width: 100%;
    height: auto;
}

/* Responsive videos */
video {
    max-width: 100%;
    height: auto;
}

/* Responsive iframes */
iframe {
    max-width: 100%;
    height: auto;
}
          </div>
          <div class="code-block">
/* CSS Grid responsive */
.grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

@media (min-width: 768px) {
    .grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (min-width: 1024px) {
    .grid {
        grid-template-columns: 1fr 1fr 1fr;
    }
}

/* Flexbox responsive */
.flex-container {
    display: flex;
    flex-direction: column;
}

@media (min-width: 768px) {
    .flex-container {
        flex-direction: row;
    }
}
          </div>
          <div class="code-block">
<!-- In HTML head -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

/* CSS for viewport units */
.full-height {
    height: 100vh;  /* viewport height */
}

.full-width {
    width: 100vw;   /* viewport width */
}

.responsive-text {
    font-size: 4vw;  /* responsive font size */
}
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other CSS Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to CSS</button>
          <button class="topic-btn" data-index="1">CSS Selectors</button>
          <button class="topic-btn" data-index="2">Colors & Backgrounds</button>
          <button class="topic-btn" data-index="3">Text Styling</button>
          <button class="topic-btn" data-index="4">Box Model</button>
          <button class="topic-btn" data-index="5">Layout & Display</button>
          <button class="topic-btn" data-index="6">Responsive Design</button>
        </div>
      </div>
    </div>
    <script>
    (function() {
        const sections = document.querySelectorAll('.lesson-section');
        let current = 0;
        const prevBtn = document.getElementById('prevLessonBtn');
        const nextBtn = document.getElementById('nextLessonBtn');
        const pageInfo = document.getElementById('lessonPageInfo');
        function showSection(idx) {
            sections.forEach((sec, i) => {
                sec.classList.toggle('active', i === idx);
            });
            prevBtn.disabled = idx === 0;
            nextBtn.disabled = idx === sections.length - 1;
            pageInfo.textContent = `Section ${idx + 1} of ${sections.length}`;
            current = idx;
        }
        prevBtn.onclick = function() {
            if (current > 0) {
                current--;
                showSection(current);
            }
        };
        nextBtn.onclick = function() {
            if (current < sections.length - 1) {
                current++;
                showSection(current);
            }
        };
        showSection(current);
        document.getElementById('otherTopicsBtn').onclick = function() {
            var dropdown = document.getElementById('otherTopicsDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        };
        document.querySelectorAll('.topic-btn').forEach(function(btn) {
            btn.onclick = function() {
                var idx = parseInt(btn.getAttribute('data-index'));
                showSection(idx);
                document.getElementById('otherTopicsDropdown').style.display = 'none';
            };
        });
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('otherTopicsDropdown');
            var btn = document.getElementById('otherTopicsBtn');
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.style.display = 'none';
            }
        });
    })();
    </script>
</body>
</html>