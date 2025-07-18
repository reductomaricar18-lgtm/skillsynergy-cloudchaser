<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoSQL Basics - SkillSynergy</title>
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
          <h2>Introduction to NoSQL</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>NoSQL databases are non-relational databases designed for scalability, flexibility, and high performance. They are ideal for handling large volumes of unstructured or semi-structured data.</p>
          <h3>Why NoSQL?</h3>
          <ul>
            <li><strong>Flexible Schema:</strong> Store data without a fixed structure</li>
            <li><strong>Scalable:</strong> Built for horizontal scaling and big data</li>
            <li><strong>High Performance:</strong> Fast read and write operations</li>
            <li><strong>Variety:</strong> Supports document, key-value, column, and graph models</li>
            <li><strong>Big Data:</strong> Ideal for real-time analytics and IoT</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> NoSQL is great for applications with rapidly changing or diverse data types.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Types of NoSQL Databases</h2>
          <ul>
            <li><strong>Document Stores:</strong> MongoDB, CouchDB</li>
            <li><strong>Key-Value Stores:</strong> Redis, DynamoDB</li>
            <li><strong>Column Stores:</strong> Cassandra, HBase</li>
            <li><strong>Graph Databases:</strong> Neo4j, ArangoDB</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Choose the NoSQL type that best fits your data and access patterns.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Document Databases</h2>
          <p>Document databases store data as JSON-like documents. Each document can have a different structure.</p>
          <h3>Example (MongoDB):</h3>
          <div class="code-block">
{
  "name": "Alice",
  "age": 25,
  "skills": ["JavaScript", "Node.js"]
}
          </div>
          <h3>Query Example</h3>
          <div class="code-block">
db.users.find({ age: { $gt: 20 } });
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Key-Value Stores</h2>
          <p>Key-value stores are simple databases that store data as key-value pairs. They are extremely fast and scalable.</p>
          <h3>Example (Redis):</h3>
          <div class="code-block">
SET user:1 "Alice"
GET user:1
          </div>
          <h3>Example (DynamoDB):</h3>
          <div class="code-block">
{
  "userId": "1",
  "name": "Alice"
}
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Column Stores</h2>
          <p>Column stores organize data in columns rather than rows, making them efficient for analytical queries.</p>
          <h3>Example (Cassandra):</h3>
          <div class="code-block">
CREATE TABLE users (
  user_id UUID PRIMARY KEY,
  name TEXT,
  age INT
);
          </div>
          <h3>Insert Data</h3>
          <div class="code-block">
INSERT INTO users (user_id, name, age) VALUES (uuid(), 'Alice', 25);
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Graph Databases</h2>
          <p>Graph databases store data as nodes and edges, making them ideal for representing relationships.</p>
          <h3>Example (Cypher - Neo4j):</h3>
          <div class="code-block">
CREATE (a:Person {name: 'Alice'})
CREATE (b:Person {name: 'Bob'})
CREATE (a)-[:FRIEND]->(b)
          </div>
          <h3>Query Example</h3>
          <div class="code-block">
MATCH (a:Person)-[:FRIEND]->(b:Person) RETURN a, b;
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>NoSQL Tools</h2>
          <ul>
            <li><strong>MongoDB Compass:</strong> GUI for MongoDB</li>
            <li><strong>RedisInsight:</strong> GUI for Redis</li>
            <li><strong>Cassandra Query Language (CQL) Shell:</strong> CLI for Cassandra</li>
            <li><strong>Neo4j Browser:</strong> Web interface for Neo4j</li>
            <li><strong>Cloud Services:</strong> MongoDB Atlas, AWS DynamoDB, Azure Cosmos DB</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other NoSQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to NoSQL</button>
          <button class="topic-btn" data-index="1">Types of NoSQL Databases</button>
          <button class="topic-btn" data-index="2">Document Databases</button>
          <button class="topic-btn" data-index="3">Key-Value Stores</button>
          <button class="topic-btn" data-index="4">Column Stores</button>
          <button class="topic-btn" data-index="5">Graph Databases</button>
          <button class="topic-btn" data-index="6">NoSQL Tools</button>
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