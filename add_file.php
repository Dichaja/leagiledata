<?php
session_start();
require_once('bin/page_settings.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('bin/source_links.php'); ?>
</head>
<body>
 <!-- Header Section -->
 <?php siteHeader() ?>
<main class="flex-grow">
  <div class="container mx-auto px-4 py-8">
    <form action="fetch/register_report.php" method="POST" enctype="multipart/form-data">
      <div class="max-w-3xl mx-auto rounded-xl border bg-card text-card-foreground shadow">
  <!-- Header -->
  <div class="flex flex-col space-y-1.5 p-6">
    <h3 class="text-2xl font-semibold tracking-tight">Add New File</h3>
  </div>

  <!-- Form Content -->
  <div class="p-6 pt-0">
    <form class="space-y-6">
      <!-- Report Title -->
      <div class="space-y-2">
        <label class="text-sm font-medium leading-none" for="title">Report Title</label>
        <input 
          class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
          placeholder="Enter the title of your research report" 
          name="title" 
          id="title"
        >
      </div>

      <!-- Author -->
      <div class="space-y-2">
        <label class="text-sm font-medium leading-none" for="author">Author</label>
        <input 
          class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
          placeholder="Enter author name or credentials" 
          name="author" 
          id="author"
        >
      </div>

      <!-- Description -->
      <div class="space-y-2">
        <label class="text-sm font-medium leading-none" for="description">Description</label>
        <textarea 
          class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring min-h-32" 
          placeholder="Provide a detailed description of your research report" 
          name="description" 
          id="description"
        ></textarea>
      </div>

      <!-- Category & Price Row -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Category -->
        <div class="space-y-2">
          <label class="text-sm font-medium leading-none" for="category">Category</label>
          <select 
            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
            name="category" 
            id="category"
          >
            <option value="">Select a category</option>
            <option value="Academic Research">Academic Research</option>
            <option value="Market Analysis">Market Analysis</option>
            <option value="Scientific Studies">Scientific Studies</option>
            <option value="Industry Reports">Industry Reports</option>
            <option value="Business Strategy">Business Strategy</option>
          </select>
        </div>

        <!-- Price -->
        <div class="space-y-2">
          <label class="text-sm font-medium leading-none" for="price">Price ($)</label>
          <input 
            type="number" 
            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" 
            step="0.01" 
            min="0" 
            placeholder="Enter price" 
            name="price" 
            id="price" 
            value="0"
          >
        </div>
      </div>

      <!-- Thumbnail Upload -->
      <div class="space-y-2">
        <label class="text-sm font-medium leading-none" for="thumbnail">Upload Thumbnail Image</label>
        <input type="file" accept="image/*" name="thumbnail" id="thumbnail" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
        <p class="text-[0.8rem] text-muted-foreground">Upload an image to represent your report (JPG, PNG, GIF, WEBP)</p>
      </div>

      <!-- PDF Upload -->
      <div class="space-y-2">
        <label class="text-sm font-medium leading-none" for="file">Upload Report PDF</label>
        <div class="flex items-center gap-2">
          <input type="file" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"   accept=".pdf" name="file" id="file">
          <button class="inline-flex items-center justify-center h-9 w-9 rounded-md border border-input bg-background shadow-sm hover:bg-accent" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="17 8 12 3 7 8"></polyline>
              <line x1="12" x2="12" y1="3" y2="15"></line>
            </svg>
          </button>
        </div>
        <p class="text-[0.8rem] text-muted-foreground">Upload your research report as a PDF file (max 50MB)</p>
      </div>

      <!-- Page Count -->
      <div class="space-y-2">
        <label class="text-sm font-medium leading-none" for="page_count">Page Count</label>
        <input type="number" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" min="1" name="page_count" id="page_count"  value="0">
        <p class="text-[0.8rem] text-muted-foreground">Enter the number of pages in your report</p>
      </div>

      <!-- Success/Error Message -->
      <div id="report-status" class="text-sm text-muted-foreground mb-2"></div>
      <!-- Form Actions -->
      <div class="flex justify-end gap-4">
        <button class="inline-flex items-center justify-center h-9 px-4 py-2 rounded-md border border-input bg-background shadow-sm hover:bg-accent" type="button" id="cancelBtn">
          Cancel
        </button>
        <button class="inline-flex items-center justify-center h-9 px-4 py-2 rounded-md bg-primary text-primary-foreground shadow hover:bg-primary/90 min-w-[120px]" type="submit" id="submitBtn">
          Submit Report
        </button>
      </div>
    </form>
  </div>
</div>

    </form>
  </div>
</div>

<script>
document.querySelector('form.space-y-6').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  document.getElementById('report-status').textContent = '';
  document.getElementById('submitBtn').disabled = true;
  fetch('fetch/register_report.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById('submitBtn').disabled = false;
    if (data.success) {
      document.getElementById('report-status').textContent = data.message;
      document.getElementById('report-status').classList.add('text-green-600');
      form.reset();
    } else {
      document.getElementById('report-status').textContent = data.error || 'Submission failed.';
      document.getElementById('report-status').classList.remove('text-green-600');
      document.getElementById('report-status').classList.add('text-red-600');
    }
  })
  .catch(() => {
    document.getElementById('submitBtn').disabled = false;
    document.getElementById('report-status').textContent = 'Submission failed.';
    document.getElementById('report-status').classList.remove('text-green-600');
    document.getElementById('report-status').classList.add('text-red-600');
  });
});
document.getElementById('cancelBtn').addEventListener('click', function() {
  document.querySelector('form.space-y-6').reset();
  document.getElementById('report-status').textContent = '';
});
</script>
</main>
<!-- footer section -->
<?php siteFooter() ?>
</body>
</html>