<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>SQL Basics - SkillSynergy</title>
    
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
          <h2>SQL Introduction</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>SQL (Structured Query Language) is used to manage and manipulate relational databases.</p>
          <h3>What is SQL?</h3>
          <ul>
            <li>Creating and modifying database structures</li>
            <li>Inserting, updating, and deleting data</li>
            <li>Querying and retrieving data</li>
            <li>Managing database security</li>
          </ul>
          <h3>Database Concepts</h3>
          <div class="code-block">
-- Database: A collection of related data
-- Table: A structured collection of data
-- Column: A field in a table
-- Row: A record in a table
-- Primary Key: Unique identifier for each row
-- Foreign Key: Reference to another table's primary key
          </div>
          <h3>Sample Table Structure</h3>
          <div class="code-block">
-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total DECIMAL(10,2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
          </div>
          <h3>SQL Commands Categories</h3>
          <div class="code-block">
-- DDL (Data Definition Language)
CREATE, ALTER, DROP, TRUNCATE

-- DML (Data Manipulation Language)
SELECT, INSERT, UPDATE, DELETE

-- DCL (Data Control Language)
GRANT, REVOKE

-- TCL (Transaction Control Language)
COMMIT, ROLLBACK, SAVEPOINT
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>SELECT Statements</h2>
          <p>The SELECT statement is used to retrieve data from database tables.</p>
          <h3>Basic SELECT</h3>
          <div class="code-block">
-- Select all columns from a table
SELECT * FROM users;

-- Select specific columns
SELECT name, email FROM users;

-- Select with aliases
SELECT name AS user_name, email AS user_email FROM users;
          </div>
          <h3>DISTINCT and LIMIT</h3>
          <div class="code-block">
-- Select unique values
SELECT DISTINCT city FROM users;

-- Limit number of results
SELECT * FROM users LIMIT 10;

-- Limit with offset
SELECT * FROM users LIMIT 10 OFFSET 20;
-- Same as: SELECT * FROM users LIMIT 20, 10;
          </div>
          <h3>ORDER BY</h3>
          <div class="code-block">
-- Sort by one column
SELECT * FROM users ORDER BY name;

-- Sort in descending order
SELECT * FROM users ORDER BY created_at DESC;

-- Sort by multiple columns
SELECT * FROM users ORDER BY name ASC, created_at DESC;
          </div>
          <h3>Mathematical Operations</h3>
          <div class="code-block">
-- Basic calculations
SELECT price, quantity, price * quantity AS total FROM products;

-- Using functions
SELECT 
    price,
    ROUND(price * 1.1, 2) AS price_with_tax
FROM products;

-- Conditional calculations
SELECT 
    name,
    salary,
    CASE 
        WHEN salary > 50000 THEN 'High'
        WHEN salary > 30000 THEN 'Medium'
        ELSE 'Low'
    END AS salary_level
FROM employees;
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>WHERE Clauses</h2>
          <p>The WHERE clause filters records based on specified conditions.</p>
          <h3>Comparison Operators</h3>
          <div class="code-block">
-- Equal to
SELECT * FROM users WHERE age = 25;

-- Not equal to
SELECT * FROM users WHERE status != 'inactive';

-- Greater than, less than
SELECT * FROM products WHERE price > 100;
SELECT * FROM orders WHERE total < 50.00;

-- Greater than or equal to
SELECT * FROM users WHERE age >= 18;

-- Less than or equal to
SELECT * FROM products WHERE stock <= 10;
          </div>
          <h3>Logical Operators</h3>
          <div class="code-block">
-- AND operator
SELECT * FROM users WHERE age >= 18 AND status = 'active';

-- OR operator
SELECT * FROM products WHERE category = 'electronics' OR price > 500;

-- NOT operator
SELECT * FROM users WHERE NOT status = 'inactive';

-- Combining operators
SELECT * FROM orders 
WHERE (total > 100 AND status = 'completed') 
   OR (user_id = 5 AND created_at > '2024-01-01');
          </div>
          <h3>IN and BETWEEN</h3>
          <div class="code-block">
-- IN operator
SELECT * FROM users WHERE city IN ('New York', 'Los Angeles', 'Chicago');

-- NOT IN
SELECT * FROM products WHERE category NOT IN ('electronics', 'clothing');

-- BETWEEN operator
SELECT * FROM users WHERE age BETWEEN 18 AND 65;

-- NOT BETWEEN
SELECT * FROM orders WHERE total NOT BETWEEN 10 AND 1000;
          </div>
          <h3>LIKE and Pattern Matching</h3>
          <div class="code-block">
-- LIKE with wildcards
SELECT * FROM users WHERE name LIKE 'John%';  -- Starts with John
SELECT * FROM users WHERE email LIKE '%@gmail.com';  -- Ends with @gmail.com
SELECT * FROM users WHERE name LIKE '%an%';  -- Contains 'an'

-- Using underscore for single character
SELECT * FROM users WHERE name LIKE 'J_n';  -- J followed by any char, then n

-- NOT LIKE
SELECT * FROM users WHERE email NOT LIKE '%@gmail.com';
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>JOIN Operations</h2>
          <p>JOINs combine data from multiple tables based on related columns.</p>
          <h3>INNER JOIN</h3>
          <div class="code-block">
-- Basic INNER JOIN
SELECT users.name, orders.total
FROM users
INNER JOIN orders ON users.id = orders.user_id;

-- INNER JOIN with multiple conditions
SELECT u.name, o.total, o.order_date
FROM users u
INNER JOIN orders o ON u.id = o.user_id
WHERE o.total > 100;
          </div>
          <h3>LEFT JOIN</h3>
          <div class="code-block">
-- LEFT JOIN (includes all records from left table)
SELECT users.name, orders.total
FROM users
LEFT JOIN orders ON users.id = orders.user_id;

-- LEFT JOIN with WHERE to find users without orders
SELECT users.name
FROM users
LEFT JOIN orders ON users.id = orders.user_id
WHERE orders.id IS NULL;
          </div>
          <h3>RIGHT JOIN</h3>
          <div class="code-block">
-- RIGHT JOIN (includes all records from right table)
SELECT users.name, orders.total
FROM users
RIGHT JOIN orders ON users.id = orders.user_id;

-- RIGHT JOIN with WHERE to find orphaned orders
SELECT orders.id, orders.total
FROM users
RIGHT JOIN orders ON users.id = orders.user_id
WHERE users.id IS NULL;
          </div>
          <h3>Multiple JOINs</h3>
          <div class="code-block">
-- Joining three tables
SELECT 
    u.name,
    p.name AS product_name,
    oi.quantity,
    o.total
FROM users u
INNER JOIN orders o ON u.id = o.user_id
INNER JOIN order_items oi ON o.id = oi.order_id
INNER JOIN products p ON oi.product_id = p.id;
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>SQL Functions</h2>
          <p>SQL provides built-in functions for data manipulation and calculations.</p>
          <h3>String Functions</h3>
          <div class="code-block">
-- CONCAT - combine strings
SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM users;

-- UPPER and LOWER
SELECT UPPER(name) AS upper_name, LOWER(email) AS lower_email FROM users;

-- LENGTH
SELECT name, LENGTH(name) AS name_length FROM users;

-- SUBSTRING
SELECT name, SUBSTRING(name, 1, 3) AS first_three FROM users;

-- TRIM
SELECT TRIM('  hello  ') AS trimmed_text;
          </div>
          <h3>Numeric Functions</h3>
          <div class="code-block">
-- ROUND
SELECT price, ROUND(price, 2) AS rounded_price FROM products;

-- CEILING and FLOOR
SELECT price, CEILING(price) AS ceiling_price, FLOOR(price) AS floor_price FROM products;

-- ABS (absolute value)
SELECT ABS(-15) AS absolute_value;

-- RANDOM
SELECT RAND() AS random_number;
          </div>
          <h3>Date Functions</h3>
          <div class="code-block">
-- Current date and time
SELECT NOW() AS current_datetime;
SELECT CURDATE() AS current_date;
SELECT CURTIME() AS current_time;

-- Date arithmetic
SELECT DATE_ADD(created_at, INTERVAL 1 DAY) AS tomorrow FROM users;
SELECT DATE_SUB(created_at, INTERVAL 1 MONTH) AS last_month FROM users;

-- Date formatting
SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS formatted_date FROM users;
SELECT DATE_FORMAT(created_at, '%W, %M %d, %Y') AS full_date FROM users;
          </div>
          <h3>Conditional Functions</h3>
          <div class="code-block">
-- IF function
SELECT name, IF(age >= 18, 'Adult', 'Minor') AS age_group FROM users;

-- CASE statement
SELECT 
    name,
    CASE 
        WHEN age < 18 THEN 'Child'
        WHEN age < 65 THEN 'Adult'
        ELSE 'Senior'
    END AS age_category
FROM users;

-- COALESCE (returns first non-null value)
SELECT COALESCE(middle_name, 'N/A') AS middle_name FROM users;

-- NULLIF (returns null if values are equal)
SELECT NULLIF(price, 0) AS adjusted_price FROM products;
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>GROUP BY & Aggregation</h2>
          <p>GROUP BY groups rows and aggregate functions perform calculations on groups.</p>
          <h3>Aggregate Functions</h3>
          <div class="code-block">
-- COUNT
SELECT COUNT(*) AS total_users FROM users;
SELECT COUNT(DISTINCT city) AS unique_cities FROM users;

-- SUM
SELECT SUM(total) AS total_sales FROM orders;

-- AVG
SELECT AVG(price) AS average_price FROM products;

-- MAX and MIN
SELECT MAX(price) AS highest_price, MIN(price) AS lowest_price FROM products;
          </div>
          <h3>GROUP BY</h3>
          <div class="code-block">
-- Group by one column
SELECT city, COUNT(*) AS user_count
FROM users
GROUP BY city;

-- Group by multiple columns
SELECT city, status, COUNT(*) AS count
FROM users
GROUP BY city, status;

-- Group by with aggregate functions
SELECT 
    category,
    COUNT(*) AS product_count,
    AVG(price) AS avg_price,
    SUM(stock) AS total_stock
FROM products
GROUP BY category;
          </div>
          <h3>HAVING Clause</h3>
          <div class="code-block">
-- HAVING filters grouped results
SELECT city, COUNT(*) AS user_count
FROM users
GROUP BY city
HAVING COUNT(*) > 10;

-- HAVING with aggregate conditions
SELECT 
    category,
    AVG(price) AS avg_price
FROM products
GROUP BY category
HAVING AVG(price) > 100;
          </div>
          <h3>Complex Grouping</h3>
          <div class="code-block">
-- Group by with JOIN
SELECT 
    u.city,
    COUNT(o.id) AS order_count,
    SUM(o.total) AS total_sales
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.city;

-- Group by with date functions
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') AS month,
    COUNT(*) AS new_users
FROM users
GROUP BY DATE_FORMAT(created_at, '%Y-%m')
ORDER BY month;
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other SQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">SQL Introduction</button>
          <button class="topic-btn" data-index="1">SELECT Statements</button>
          <button class="topic-btn" data-index="2">WHERE Clauses</button>
          <button class="topic-btn" data-index="3">JOIN Operations</button>
          <button class="topic-btn" data-index="4">SQL Functions</button>
          <button class="topic-btn" data-index="5">GROUP BY & Aggregation</button>
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