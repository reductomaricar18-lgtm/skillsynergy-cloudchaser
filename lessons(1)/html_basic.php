<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>HTML Basics - SkillSynergy</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .lesson-card {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
        }

        .lesson-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .lesson-scrollable {
            flex: 1;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 30px;
            position: relative;
        }

        .lesson-section { 
            display: none; 
            animation: fadeIn 0.5s ease;
        }
        
        .lesson-section.active { 
            display: block; 
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .lesson-section h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .lesson-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .lesson-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #4a5568;
            margin: 30px 0 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lesson-section h3::before {
            content: '🚀';
            font-size: 1.3rem;
        }

        .lesson-section p {
            line-height: 1.8;
            margin-bottom: 20px;
            color: #4a5568;
            font-size: 1.1rem;
        }

        .lesson-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 25px 40px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .lesson-nav button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .lesson-nav button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .lesson-nav button:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #otherTopicsDropdown {
            display: none;
            position: absolute;
            bottom: 60px;
            right: 20px;
            min-width: 250px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 10;
            padding: 10px 0;
            backdrop-filter: blur(20px);
        }

        #otherTopicsDropdown .topic-btn {
            display: block;
            width: 100%;
            border: none;
            background: none;
            padding: 12px 20px;
            text-align: left;
            font-size: 1rem;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #otherTopicsDropdown .topic-btn:hover {
            background: linear-gradient(135deg, #f0f4ff, #e6f3ff);
            color: #667eea;
            transform: translateX(5px);
        }

        /* Enhanced Code Blocks */
        .code-block { 
            background: linear-gradient(135deg, #2d3748, #1a202c);
            color: #e2e8f0; 
            padding: 30px; 
            border-radius: 15px; 
            margin: 25px 0; 
            overflow-x: auto; 
            font-family: 'Fira Code', 'Courier New', monospace; 
            font-size: 15px; 
            line-height: 1.6;
            position: relative;
            border: 1px solid #4a5568;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .code-block::before {
            content: '💻';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 18px;
        }

        .code-comment { color: #68d391; font-style: italic; }
        .code-keyword { color: #f6ad55; font-weight: 600; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; font-weight: 600; }
        .code-function { color: #81e6d9; }
        .code-class { color: #d6bcfa; }

        /* Enhanced Info Boxes */
        .example-box { 
            background: linear-gradient(135deg, #f0fff4, #c6f6d5);
            border: 2px solid #68d391; 
            border-radius: 15px; 
            padding: 30px; 
            margin: 25px 0;
            position: relative;
            overflow: hidden;
        }

        .example-box::before {
            content: '✅';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            opacity: 0.3;
        }

        .example-box h4 { 
            color: #2f855a; 
            margin-top: 0; 
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .tip-box { 
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
            border: 2px solid #81e6d9; 
            border-radius: 15px; 
            padding: 30px; 
            margin: 25px 0;
            position: relative;
        }

        .tip-box::before {
            content: '💡';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            opacity: 0.3;
        }

        .tip-box strong { 
            color: #2c7a7b;
            font-weight: 600;
        }

        /* Enhanced Lists */
        .lesson-section ul, .lesson-section ol { 
            margin: 25px 0; 
            padding-left: 0;
            list-style: none;
        }

        .lesson-section li { 
            background: white;
            margin: 12px 0;
            padding: 18px 25px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .lesson-section li:hover {
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .lesson-section li::before {
            content: '🚀';
            font-size: 20px;
        }

        .lesson-section strong { 
            color: #2d3748; 
            font-weight: 600;
        }

        .highlight { 
            background: linear-gradient(135deg, #fef5e7, #fed7aa);
            padding: 4px 8px; 
            border-radius: 6px; 
            font-weight: bold;
            color: #c05621;
        }

        /* Interactive Elements */
        .interactive-link {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            margin: 15px 0;
        }

        .interactive-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .lesson-card {
                margin: 10px;
                height: calc(100vh - 20px);
            }

            .lesson-scrollable {
                padding: 20px;
            }

            .lesson-section h2 {
                font-size: 1.8rem;
            }

            .lesson-nav {
                flex-direction: column;
                gap: 15px;
            }

            .lesson-nav button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to HTML</h2>
          <p>HTML stands for HyperText Markup Language. It is the standard markup language for creating web pages and describes the structure of a web page.</p>
          <h3>What is HTML?</h3>
          <p>HTML is made up of elements that describe different types of content: paragraphs, links, headings, images, video, tables, etc. Each element is written with a start tag, content, and an end tag. For example, &lt;p&gt;This is a paragraph&lt;/p&gt;.</p>
          <h3>Why Use HTML?</h3>
          <p>HTML is the foundation of the web. It defines the structure and content of a webpage. Without HTML, a browser wouldn't know how to display text, images, or other elements. It's like the skeleton of a webpage.</p>
          <h3>HTML Versions</h3>
          <p>HTML has evolved over the years. The current version is HTML5, which is the latest and most widely supported version. HTML4.01 and XHTML 1.0 are also widely used.</p>
          <h3>HTML Document Structure</h3>
          <div class="code-block">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Page Title&lt;/title&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="description" content="Page description"&gt;
    &lt;link rel="stylesheet" href="styles.css"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- All content goes here --&gt;
&lt;/body&gt;
&lt;/html&gt;
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>HTML Elements</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>HTML elements are the building blocks of HTML documents. They tell the browser how to display the content.</p>
          <div class="code-block">
&lt;!-- Paragraph --&gt;
&lt;p&gt;This is a paragraph. It's a block element.&lt;/p&gt;

&lt;!-- Heading --&gt;
&lt;h1&gt;Main Heading&lt;/h1&gt;
&lt;h2&gt;Sub Heading&lt;/h2&gt;
&lt;h3&gt;Section Heading&lt;/h3&gt;
&lt;h4&gt;Subsection Heading&lt;/h4&gt;
&lt;h5&gt;Minor Heading&lt;/h5&gt;
&lt;h6&gt;Smallest Heading&lt;/h6&gt;
          </div>
          <div class="code-block">
&lt;!-- Bold text --&gt;
&lt;strong&gt;This is bold text&lt;/strong&gt;

&lt;!-- Italic text --&gt;
&lt;em&gt;This is italic text&lt;/em&gt;

&lt;!-- Marked text --&gt;
&lt;mark&gt;This is highlighted text&lt;/mark&gt;

&lt;!-- Deleted text --&gt;
&lt;del&gt;This is deleted text&lt;/del&gt;

&lt;!-- Subscript --&gt;
&lt;sub&gt;This is subscript&lt;/sub&gt;

&lt;!-- Superscript --&gt;
&lt;sup&gt;This is superscript&lt;/sup&gt;
          </div>
          <div class="code-block">
&lt;!-- Line break --&gt;
&lt;br&gt;

&lt;!-- Horizontal rule --&gt;
&lt;hr&gt;
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>HTML Attributes</h2>
          <p>Attributes provide additional information about HTML elements. They are always specified in the start tag.</p>
          <div class="code-block">
&lt;!-- ID --&gt;
&lt;p id="unique-paragraph"&gt;This paragraph has an ID.&lt;/p&gt;

&lt;!-- Class --&gt;
&lt;p class="important-text"&gt;This paragraph has a class.&lt;/p&gt;

&lt;!-- Title --&gt;
&lt;a href="https://www.example.com" title="Visit Example"&gt;Link with title&lt;/a&gt;
          </div>
          <div class="code-block">
&lt;!-- Checkbox --&gt;
&lt;input type="checkbox" checked&gt;

&lt;!-- Radio button --&gt;
&lt;input type="radio" checked&gt;
          </div>
          <div class="code-block">
&lt;!-- Onclick --&gt;
&lt;button onclick="alert('Button clicked!')"&gt;Click me&lt;/button&gt;

&lt;!-- Onchange --&gt;
&lt;select onchange="alert('Country changed!')"&gt;
    &lt;option value="us"&gt;United States&lt;/option&gt;
    &lt;option value="ca"&gt;Canada&lt;/option&gt;
&lt;/select&gt;
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>HTML Document Structure</h2>
          <p>HTML documents have a specific structure that browsers understand.</p>
          <div class="code-block">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;My First Web Page&lt;/title&gt;
    &lt;meta charset="UTF-8"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Hello, World!&lt;/h1&gt;
    &lt;p&gt;This is my first HTML page.&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
          </div>
          <div class="code-block">
&lt;!DOCTYPE html&gt;
          </div>
          <p>The DOCTYPE declaration tells the browser that this is an HTML5 document.</p>
          <div class="code-block">
&lt;html&gt;
    &lt;!-- All content goes here --&gt;
&lt;/html&gt;
          </div>
          <p>The &lt;html&gt; element is the root element of an HTML page.</p>
          <div class="code-block">
&lt;head&gt;
    &lt;title&gt;Page Title&lt;/title&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="description" content="Page description"&gt;
    &lt;link rel="stylesheet" href="styles.css"&gt;
&lt;/head&gt;
          </div>
          <p>The &lt;head&gt; section contains metadata about the document.</p>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>HTML Forms</h2>
          <p>Forms allow users to input data and submit it to a server.</p>
          <div class="code-block">
&lt;form action="/submit" method="POST"&gt;
    &lt;label for="username"&gt;Username:&lt;/label&gt;
    &lt;input type="text" id="username" name="username" required&gt;
    
    &lt;label for="email"&gt;Email:&lt;/label&gt;
    &lt;input type="email" id="email" name="email" required&gt;
    
    &lt;button type="submit"&gt;Submit&lt;/button&gt;
&lt;/form&gt;
          </div>
          <div class="code-block">
&lt;!-- Text input --&gt;
&lt;input type="text" name="fullname" placeholder="Enter your name"&gt;

&lt;!-- Password input --&gt;
&lt;input type="password" name="password"&gt;

&lt;!-- Email input --&gt;
&lt;input type="email" name="email"&gt;

&lt;!-- Number input --&gt;
&lt;input type="number" name="age" min="0" max="120"&gt;

&lt;!-- Date input --&gt;
&lt;input type="date" name="birthdate"&gt;

&lt;!-- File input --&gt;
&lt;input type="file" name="upload"&gt;

&lt;!-- Checkbox --&gt;
&lt;input type="checkbox" name="subscribe" id="subscribe"&gt;
&lt;label for="subscribe"&gt;Subscribe to newsletter&lt;/label&gt;

&lt;!-- Radio buttons --&gt;
&lt;input type="radio" name="gender" value="male" id="male"&gt;
&lt;label for="male"&gt;Male&lt;/label&gt;
&lt;input type="radio" name="gender" value="female" id="female"&gt;
&lt;label for="female"&gt;Female&lt;/label&gt;
          </div>
          <div class="code-block">
&lt;!-- Textarea --&gt;
&lt;label for="message"&gt;Message:&lt;/label&gt;
&lt;textarea id="message" name="message" rows="4" cols="50"&gt;&lt;/textarea&gt;

&lt;!-- Select dropdown --&gt;
&lt;label for="country"&gt;Country:&lt;/label&gt;
&lt;select id="country" name="country"&gt;
    &lt;option value=""&gt;Select a country&lt;/option&gt;
    &lt;option value="us"&gt;United States&lt;/option&gt;
    &lt;option value="ca"&gt;Canada&lt;/option&gt;
    &lt;option value="uk"&gt;United Kingdom&lt;/option&gt;
&lt;/select&gt;
          </div>
          <div class="code-block">
&lt;form&gt;
    &lt;!-- Required field --&gt;
    &lt;input type="text" name="username" required&gt;
    
    &lt;!-- Pattern validation --&gt;
    &lt;input type="text" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"&gt;
    
    &lt;!-- Min/max length --&gt;
    &lt;input type="text" name="zipcode" minlength="5" maxlength="10"&gt;
    
    &lt;!-- Min/max values --&gt;
    &lt;input type="number" name="age" min="18" max="65"&gt;
    
    &lt;button type="submit"&gt;Submit&lt;/button&gt;
&lt;/form&gt;
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>HTML Tables</h2>
          <p>Tables are used to display data in a structured format.</p>
          <div class="code-block">
&lt;table border="1"&gt;
    &lt;thead&gt;
        &lt;tr&gt;
            &lt;th&gt;Header 1&lt;/th&gt;
            &lt;th&gt;Header 2&lt;/th&gt;
        &lt;/tr&gt;
    &lt;/thead&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;Data 1&lt;/td&gt;
            &lt;td&gt;Data 2&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Data 3&lt;/td&gt;
            &lt;td&gt;Data 4&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
          </div>
          <div class="code-block">
&lt;!-- Simple header --&gt;
&lt;th&gt;Header&lt;/th&gt;

&lt;!-- Header with colspan --&gt;
&lt;th colspan="2"&gt;Header&lt;/th&gt;

&lt;!-- Header with rowspan --&gt;
&lt;th rowspan="2"&gt;Header&lt;/th&gt;
          </div>
          <div class="code-block">
&lt;!-- Simple row --&gt;
&lt;tr&gt;
    &lt;td&gt;Cell 1&lt;/td&gt;
    &lt;td&gt;Cell 2&lt;/td&gt;
&lt;/tr&gt;

&lt;!-- Row with colspan --&gt;
&lt;tr&gt;
    &lt;td colspan="2"&gt;Cell&lt;/td&gt;
&lt;/tr&gt;

&lt;!-- Row with rowspan --&gt;
&lt;tr&gt;
    &lt;td rowspan="2"&gt;Cell&lt;/td&gt;
    &lt;td&gt;Cell&lt;/td&gt;
&lt;/tr&gt;
          </div>
          <div class="code-block">
&lt;!-- Simple cell --&gt;
&lt;td&gt;Data&lt;/td&gt;

&lt;!-- Cell with colspan --&gt;
&lt;td colspan="2"&gt;Data&lt;/td&gt;

&lt;!-- Cell with rowspan --&gt;
&lt;td rowspan="2"&gt;Data&lt;/td&gt;
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Practical Tips for HTML</h2>
          <p>Here are some tips to help you write better HTML code.</p>
          <h3>1. File Extensions</h3>
          <p>HTML files typically have a .html or .htm extension. For example, index.html, about.html, styles.css, scripts.js.</p>
          <h3>2. File Organization</h3>
          <p>Keep your HTML files organized. Use a clear file naming convention. For example, main.html, header.html, footer.html, content.html.</p>
          <h3>3. Validation</h3>
          <p>Always validate your HTML code using an online validator like the W3C Markup Validation Service. This helps catch errors and ensure your code is standards-compliant.</p>
          <h3>4. Semantics</h3>
          <p>Use semantic HTML elements (like &lt;header&gt;, &lt;nav&gt;, &lt;main&gt;, &lt;article&gt;, &lt;section&gt;, &lt;footer&gt;) to make your code more meaningful and easier to understand.</p>
          <h3>5. Comments</h3>
          <p>Use comments (&lt;!-- This is a comment --&gt;) to explain your code. This helps other developers understand your code and makes it easier to maintain.</p>
          <h3>6. Debugging</h3>
          <p>Use browser developer tools (F12) to inspect your HTML elements, check for errors, and see how your page is rendered. This is a powerful tool for debugging.</p>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other HTML Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to HTML</button>
          <button class="topic-btn" data-index="1">HTML Elements</button>
          <button class="topic-btn" data-index="2">HTML Attributes</button>
          <button class="topic-btn" data-index="3">HTML Document Structure</button>
          <button class="topic-btn" data-index="4">HTML Forms</button>
          <button class="topic-btn" data-index="5">HTML Tables</button>
          <button class="topic-btn" data-index="6">Practical Tips</button>
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