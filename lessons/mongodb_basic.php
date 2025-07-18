<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MongoDB Basics - SkillSynergy</title>
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
          <h2>Introduction to MongoDB</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>MongoDB is a popular open-source NoSQL database that stores data in flexible, JSON-like documents. It is designed for scalability, high performance, and ease of development.</p>
          <h3>Why MongoDB?</h3>
          <ul>
            <li><strong>Flexible Schema:</strong> Store data without a fixed structure</li>
            <li><strong>Scalable:</strong> Built for horizontal scaling and big data</li>
            <li><strong>High Performance:</strong> Fast read and write operations</li>
            <li><strong>Rich Query Language:</strong> Powerful queries and aggregations</li>
            <li><strong>Document-Oriented:</strong> Store complex data types easily</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> MongoDB is ideal for applications with rapidly changing or unstructured data.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Installation & Setup</h2>
          <p>To get started with MongoDB, download and install it from the official website or use a cloud service like MongoDB Atlas.</p>
          <h3>Install MongoDB (Local)</h3>
          <div class="code-block">
# On Ubuntu
sudo apt-get install -y mongodb

# On macOS (Homebrew)
brew tap mongodb/brew
brew install mongodb-community
          </div>
          <h3>Start MongoDB</h3>
          <div class="code-block">
# Start the MongoDB service
sudo service mongodb start

# Or (macOS)
brew services start mongodb-community
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use MongoDB Atlas for managed cloud databases.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Basic CRUD Operations</h2>
          <p>CRUD stands for Create, Read, Update, and Delete. These are the basic operations for managing data in MongoDB.</p>
          <h3>Insert Documents</h3>
          <div class="code-block">
// Insert one document
 db.collection.insertOne({ name: "Alice", age: 25 });

// Insert multiple documents
 db.collection.insertMany([
   { name: "Bob", age: 30 },
   { name: "Carol", age: 22 }
 ]);
          </div>
          <h3>Find Documents</h3>
          <div class="code-block">
// Find all documents
 db.collection.find({});

// Find with filter
 db.collection.find({ age: { $gt: 20 } });
          </div>
          <h3>Update Documents</h3>
          <div class="code-block">
// Update one document
 db.collection.updateOne({ name: "Alice" }, { $set: { age: 26 } });

// Update many documents
 db.collection.updateMany({}, { $set: { active: true } });
          </div>
          <h3>Delete Documents</h3>
          <div class="code-block">
// Delete one document
 db.collection.deleteOne({ name: "Bob" });

// Delete many documents
 db.collection.deleteMany({ age: { $lt: 25 } });
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Data Modeling</h2>
          <p>Data modeling in MongoDB involves designing collections and documents to fit your application's needs.</p>
          <h3>Embedded Documents</h3>
          <div class="code-block">
{
  name: "Alice",
  address: {
    street: "123 Main St",
    city: "New York"
  }
}
          </div>
          <h3>Referencing Documents</h3>
          <div class="code-block">
{
  name: "Order 1",
  user_id: ObjectId("..."),
  items: [ObjectId("..."), ObjectId("...")]
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use embedded documents for data that is accessed together, and references for related but independent data.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Indexes</h2>
          <p>Indexes improve the performance of queries in MongoDB.</p>
          <h3>Create an Index</h3>
          <div class="code-block">
// Create an index on the 'name' field
 db.collection.createIndex({ name: 1 });
          </div>
          <h3>View Indexes</h3>
          <div class="code-block">
// List all indexes
 db.collection.getIndexes();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use indexes on fields that are frequently queried.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Aggregation</h2>
          <p>Aggregation operations process data records and return computed results.</p>
          <h3>Aggregation Pipeline Example</h3>
          <div class="code-block">
db.collection.aggregate([
  { $match: { age: { $gte: 18 } } },
  { $group: { _id: "$active", count: { $sum: 1 } } }
]);
          </div>
          <h3>Common Stages</h3>
          <ul>
            <li><strong>$match:</strong> Filter documents</li>
            <li><strong>$group:</strong> Group documents</li>
            <li><strong>$sort:</strong> Sort documents</li>
            <li><strong>$project:</strong> Reshape documents</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>MongoDB Tools</h2>
          <p>MongoDB provides several tools for managing and interacting with your databases.</p>
          <ul>
            <li><strong>mongo:</strong> The MongoDB shell for running commands</li>
            <li><strong>mongodump/mongorestore:</strong> Backup and restore data</li>
            <li><strong>mongoimport/mongoexport:</strong> Import and export data</li>
            <li><strong>Compass:</strong> GUI for visualizing and managing data</li>
            <li><strong>Atlas:</strong> Cloud-based MongoDB service</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other MongoDB Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to MongoDB</button>
          <button class="topic-btn" data-index="1">Installation & Setup</button>
          <button class="topic-btn" data-index="2">Basic CRUD Operations</button>
          <button class="topic-btn" data-index="3">Data Modeling</button>
          <button class="topic-btn" data-index="4">Indexes</button>
          <button class="topic-btn" data-index="5">Aggregation</button>
          <button class="topic-btn" data-index="6">MongoDB Tools</button>
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