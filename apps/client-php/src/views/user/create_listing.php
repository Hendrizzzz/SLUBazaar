<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Market</title>

    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/user/create_listing.css">
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>

    <div class="bg-layer-stripes"></div>
    <div class="bg-layer-building"></div>

    <nav class="sidebar">
        <div class="nav-icons">
            <a href="index.php?action=profile" title="Profile"><i class="fa-regular fa-user"></i></a>
            <a href="index.php?action=marketplace" title="Home"><i class="fa-solid fa-house"></i></a>
            <a href="index.php?action=create_listing" class="active" title="New Listing"><i
                    class="fa-solid fa-circle-plus"></i></a>
            <a href="index.php?action=chat" title="Messages"><i class="fa-regular fa-paper-plane"></i></a>
            <a href="index.php?action=logout" title="Logout" style="margin-top: 20px; color: #ef4444;"><i
                    class="fa-solid fa-right-from-bracket"></i></a>
        </div>
        <div class="bottom-user">
            <img src="https://ui-avatars.com/api/?name=User&background=random" alt="Me">
        </div>
    </nav>


    <div class="main-wrapper">
        <div class="page-container">
            <div class="page-header">
                <h2>New Listing</h2>
            </div>

            <form method="POST" class="form-content" enctype="multipart/form-data">
                <div class="form-layout-grid">
                    <div class="left-col">
                        <div class="form-row">
                            <label>Title:</label>
                            <input type="text" name="title" class="input-field" placeholder="E.g. Vintage Leather Bag"
                                required>
                        </div>
                        <div class="form-row">
                            <label>Category:</label>
                            <select name="category" class="input-field" required>
                                <option value="" disabled selected>Select Category</option>
                                <?php
                                // TODO: move this require_once to the controller
                                // retrieve categories from the enum file
                                require_once __DIR__ . '/../../models/enum/Category.php';
                                foreach (Category::cases() as $category) {
                                    echo "<option value=\"{$category->value}\">{$category->value}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Description:</label>
                            <textarea name="description" class="input-field"
                                placeholder="Describe condition, reason for selling, defects, etc." required></textarea>
                        </div>
                        <div class="sub-row">
                            <div class="form-row" style="flex: 1;">
                                <label>Starting Amount (₱):</label>
                                <input type="number" name="starting_bid" class="input-field" placeholder="0.00"
                                    required>
                            </div>
                            <div class="form-row" style="flex: 1;">
                                <label>Date Start (Optional):</label>
                                <input type="datetime-local" name="start_time" class="input-field">
                            </div>
                            <div class="form-row" style="flex: 1;">
                                <label>Date End:</label>
                                <input type="datetime-local" name="end_time" class="input-field" required>
                            </div>
                        </div>
                    </div>

                    <div class="right-col">
                        <div class="form-row">
                            <label>Insert Photos (Max 4):</label>

                            <input type="file" name="images[]" id="file1" accept="image/*" style="display:none"
                                onchange="previewImage(this, 'box1')">
                            <input type="file" name="images[]" id="file2" accept="image/*" style="display:none"
                                onchange="previewImage(this, 'box2')">
                            <input type="file" name="images[]" id="file3" accept="image/*" style="display:none"
                                onchange="previewImage(this, 'box3')">
                            <input type="file" name="images[]" id="file4" accept="image/*" style="display:none"
                                onchange="previewImage(this, 'box4')">

                            <div class="photo-grid">
                                <div class="photo-upload-box" id="box1"
                                    onclick="document.getElementById('file1').click()">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                                <div class="photo-upload-box" id="box2"
                                    onclick="document.getElementById('file2').click()">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                                <div class="photo-upload-box" id="box3"
                                    onclick="document.getElementById('file3').click()">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                                <div class="photo-upload-box" id="box4"
                                    onclick="document.getElementById('file4').click()">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            </div>
                            <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 10px; text-align:center;">Click a
                                box to upload an image</p>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-add">Add Listing</button>
            </form>
        </div>
    </div>

    <script src="/assets/js/marketplace.js"></script>

    <script>
        function previewImage(input, boxId) {
            const box = document.getElementById(boxId);
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    box.style.backgroundImage = `url('${e.target.result}')`;
                    box.innerHTML = ''; 
                    
                    box.style.borderStyle = 'solid';
                    box.style.borderColor = '#3b82f6'; // var(--primary)
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>