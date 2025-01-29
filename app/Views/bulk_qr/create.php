<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Bulk Process PDFs</h2>
    
    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->get('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('bulk-qr/process') ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="pdf_files" class="form-label">Select PDF Files</label>
            <input type="file" 
                   class="form-control" 
                   id="pdf_files" 
                   name="pdf_files[]" 
                   accept=".pdf"
                   multiple 
                   required>
            <small class="text-muted">You can select multiple PDF files by holding Ctrl (Windows) or Command (Mac) while selecting</small>
        </div>

        <div class="mb-3">
            <div id="selected_files" class="list-group">
                <!-- Selected files will be listed here -->
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Process Files</button>
        <a href="<?= base_url('bulk-qr') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('pdf_files').addEventListener('change', function(e) {
    const fileList = document.getElementById('selected_files');
    fileList.innerHTML = ''; // Clear previous list
    
    // Add each selected file to the list
    for(let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
        
        const fileItem = document.createElement('div');
        fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        fileItem.innerHTML = `
            <div>
                <i class="bi bi-file-pdf text-danger"></i>
                ${file.name}
            </div>
            <span class="badge bg-primary rounded-pill">${fileSize} MB</span>
        `;
        
        fileList.appendChild(fileItem);
    }
});

function validateForm() {
    const files = document.getElementById('pdf_files').files;
    if (files.length === 0) {
        alert('Please select at least one PDF file');
        return false;
    }
    
    let totalSize = 0;
    for (let i = 0; i < files.length; i++) {
        totalSize += files[i].size;
    }
    
    // Check if total size is more than 8MB
    if (totalSize > 8 * 1024 * 1024) {
        alert('Total file size should not exceed 8MB');
        return false;
    }
    
    return true;
}
</script>
<?= $this->endSection() ?> 