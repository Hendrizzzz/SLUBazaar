class ListingService {
    constructor(itemRepo) {
        this.itemRepo = itemRepo;
    }
    
    async getAllListings(search, status) {
        try {
            // Get all items from the repository
            const items = await this.itemRepo.getAllItemsForAdmin();
            
            // Filter by search term if provided
            let filteredItems = items;
            if (search) {
                const searchTerm = search.toLowerCase();
                filteredItems = items.filter(item => 
                    item.title.toLowerCase().includes(searchTerm) || 
                    item.description.toLowerCase().includes(searchTerm)
                );
            }
            
            // Filter by status if provided
            if (status && status !== 'all') {
                filteredItems = filteredItems.filter(item => 
                    item.itemStatus.toLowerCase() === status.toLowerCase()
                );
            }
            
            return filteredItems;
        } catch (error) {
            throw new Error(`Failed to get listings: ${error.message}`);
        }
    }
    
    async removeListing(listingId) {
        try {
            await this.itemRepo.updateItemStatus(listingId, 'Removed By Admin');
            return true;
        } catch (error) {
            throw new Error(`Failed to remove listing: ${error.message}`);
        }
    }
    
    async restoreListing(listingId) {
        try {
            await this.itemRepo.updateItemStatus(listingId, 'Active');
            return true;
        } catch (error) {
            throw new Error(`Failed to restore listing: ${error.message}`);
        }
    }
}

module.exports = ListingService;