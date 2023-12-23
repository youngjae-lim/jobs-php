<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>
<?php loadPartial('top-banner'); ?>

<section class="flex justify-center items-center mt-20">
    <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Edit Job Listing</h2>
        <form method="POST" action="/listings/<?= $listing->id ?>">
            <input type="hidden" name="_method" value="PUT" />
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Job Info
            </h2>
            <?php if (isset($errors) && count($errors) > 0) { ?>
                <?php foreach ($errors as $error) { ?>
                    <div class="message bg-red-100 p-3 my-3 text-red-700"><?php echo $error; ?></div>
                <?php } ?>
            <?php } elseif (isset($success) && $success === true) { ?>
                <div class="message bg-green-100 p-3 my-3 text-green-700">Job listing created successfully!</div>
            <?php } ?>
            <div class="mb-4">
                <input type="text" name="title" value="<?= $listing->title ?? '' ?>" placeholder="Job Title" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <textarea name="description" placeholder="Job Description" class="w-full px-4 py-2 border rounded focus:outline-none"><?= $listing->description ?? '' ?></textarea>
            </div>
            <div class="mb-4">
                <input type="text" name="salary" value="<?= $listing->salary ?? '' ?>" placeholder="Annual Salary" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="requirements" value="<?= $listing->requirements ?? '' ?>" placeholder="Requirements" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="benefits" value="<?= $listing->benefits ?? '' ?>" placeholder="Benefits" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="tags" value="<?= $listing->tags ?? '' ?>" placeholder="Tags" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Company Info & Location
            </h2>
            <div class="mb-4">
                <input type="text" name="company" value="<?= $listing->company ?? '' ?>" placeholder="Company Name" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="address" value="<?= $listing->address ?? '' ?>" placeholder="Address" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="city" value="<?= $listing->city ?? '' ?>" placeholder="City" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="state" value="<?= $listing->state ?? '' ?>" placeholder="State" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="text" name="phone" value="<?= $listing->phone ?? '' ?>" placeholder="Phone" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="email" name="email" value="<?= $listing->email ?? '' ?>" placeholder="Email Address For Applications" class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <button class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
                Save
            </button>
            <a href="/listings/<?= $listing->id ?>" class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none">
                Cancel
            </a>
        </form>
    </div>
</section>

<?php loadPartial('bottom-banner'); ?>
<?php loadPartial('footer'); ?>
