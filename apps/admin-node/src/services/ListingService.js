class ListingService {
    constructor(itemRepo) {
        this.itemRepo = itemRepo;
    }

    async getAllListings(filters) {
        return await this.itemRepo.findAll(filters);
    }

    /**
     * Soft Deletes an item by setting status to 'Removed By Admin'
     */
    async removeListing(itemId) {
        const item = await this.itemRepo.findById(itemId);
        if (!item) throw new Error("Item not found");

        return await this.itemRepo.updateStatus(itemId, 'Removed By Admin');
    }




    /**
     * Utility to restore an item if it was removed by mistake
     */
    async restoreListing(itemId) {
        const item = await this.itemRepo.findById(itemId);
        if (!item) throw new Error("Item not found");

        // Restore to 'Active' (or 'Pending' if you prefer review)
        return await this.itemRepo.updateStatus(itemId, 'Active');
    }



    /**
     * Updates the text content of a listing (Sanitization)
     */
    async updateListingContent(itemId, title, description) {
        const item = await this.itemRepo.findById(itemId);
        if (!item) throw new Error("Item not found");

        // Basic validation
        if (title.length < 3 || description.length < 5)
            throw new Error("Title or Description too short.");

        return await this.itemRepo.updateContent(itemId, title, description);
    }
}

module.exports = ListingService;