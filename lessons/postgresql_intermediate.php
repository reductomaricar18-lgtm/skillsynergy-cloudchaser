<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PostgreSQL Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            min-height: 100vh;
        }
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
        .code-comment { color: #68d391; }
        .code-keyword { color: #f6ad55; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #2c7a7b; margin-top: 0; }
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
          <h2>Joins in PostgreSQL</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Joins combine rows from two or more tables based on a related column.</p>
          <div class="code-block">
<span class="code-comment">-- INNER JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
INNER JOIN departments ON employees.dept_id = departments.id;

<span class="code-comment">-- LEFT JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
LEFT JOIN departments ON employees.dept_id = departments.id;

<span class="code-comment">-- RIGHT JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
RIGHT JOIN departments ON employees.dept_id = departments.id;

<span class="code-comment">-- FULL JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
FULL JOIN departments ON employees.dept_id = departments.id;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use the appropriate join type to control which rows appear in your results.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Subqueries</h2>
          <p>Subqueries are queries nested inside another query. They can be used in SELECT, FROM, or WHERE clauses.</p>
          <div class="code-block">
<span class="code-comment">-- Subquery in WHERE</span>
SELECT name FROM employees
WHERE dept_id = (SELECT id FROM departments WHERE dept_name = 'Sales');

<span class="code-comment">-- Subquery in FROM</span>
SELECT avg_salary FROM (
  SELECT AVG(salary) AS avg_salary FROM employees
) AS sub;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Subqueries can be correlated (reference outer query) or uncorrelated.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Indexes</h2>
          <p>Indexes speed up data retrieval. PostgreSQL supports B-tree, Hash, GIN, GiST, and more.</p>
          <div class="code-block">
<span class="code-comment">-- Create an index</span>
CREATE INDEX idx_employee_name ON employees(name);

<span class="code-comment">-- Unique index</span>
CREATE UNIQUE INDEX idx_unique_email ON employees(email);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>EXPLAIN</code> to see if your queries use indexes.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Views</h2>
          <p>Views are virtual tables based on SQL queries. They simplify complex queries and enhance security.</p>
          <div class="code-block">
<span class="code-comment">-- Create a view</span>
CREATE VIEW sales_employees AS
SELECT name, salary FROM employees WHERE dept_id = 2;

<span class="code-comment">-- Use a view</span>
SELECT * FROM sales_employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Views can be updatable if based on a single table without aggregation.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Transactions</h2>
          <p>Transactions group multiple operations into a single unit. They ensure data integrity using ACID properties.</p>
          <div class="code-block">
BEGIN;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
UPDATE accounts SET balance = balance + 100 WHERE id = 2;
COMMIT;

<span class="code-comment">-- Rollback example</span>
BEGIN;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
ROLLBACK;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use transactions for critical operations to prevent partial updates.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Constraints</h2>
          <p>Constraints enforce rules on table data. Common types: UNIQUE, CHECK, FOREIGN KEY.</p>
          <div class="code-block">
<span class="code-comment">-- UNIQUE constraint</span>
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  email TEXT UNIQUE
);

<span class="code-comment">-- CHECK constraint</span>
CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  price NUMERIC CHECK (price > 0)
);

<span class="code-comment">-- FOREIGN KEY constraint</span>
CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id)
);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Constraints help maintain data quality and relationships.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>User-Defined Functions (Basics)</h2>
          <p>Create simple functions in PostgreSQL using SQL or PL/pgSQL.</p>
          <div class="code-block">
<span class="code-comment">-- SQL function</span>
CREATE FUNCTION add_numbers(a INT, b INT) RETURNS INT AS $$
  SELECT a + b;
$$ LANGUAGE SQL;

<span class="code-comment">-- PL/pgSQL function</span>
CREATE OR REPLACE FUNCTION get_employee_count() RETURNS INT AS $$
DECLARE
  total INT;
BEGIN
  SELECT COUNT(*) INTO total FROM employees;
  RETURN total;
END;
$$ LANGUAGE plpgsql;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use functions to encapsulate logic and reuse code in queries.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other PostgreSQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Joins</button>
          <button class="topic-btn" data-index="1">Subqueries</button>
          <button class="topic-btn" data-index="2">Indexes</button>
          <button class="topic-btn" data-index="3">Views</button>
          <button class="topic-btn" data-index="4">Transactions</button>
          <button class="topic-btn" data-index="5">Constraints</button>
          <button class="topic-btn" data-index="6">User-Defined Functions</button>
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