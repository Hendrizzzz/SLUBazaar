const ListingService = require('../services/ListingService');

/**
 * Controller for the Listings Management section
 */
class ListingController {
    constructor(listingService){
        this.listingService = listingService;
    }
 
    async viewListings(req, res) {
        try {
            const { search, status } = req.query;
            const listings = await this.listingService.getAllListings(search, status);
            
            res.render('listings/listings', {
                title: 'Listing Management',
                currentPage: 'listings',
                listings: listings,
                searchQuery: search || '',
                statusFilter: status || 'all'
            });
        } catch (error) {
            console.error('Error loading listings:', error);
            res.status(500).render('error', {
                title: 'Listings Error',  // Added title here
                message: 'Failed to load listings'
            });
        }
    }

  
    async removeListing(req, res) {
        try {
            const listingId = req.params.id;
            await this.listingService.removeListing(listingId);
            
            res.json({ success: true, message: 'Listing removed successfully' });
        } catch (error) {
            console.error('Error removing listing:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to remove listing' 
            });
        }
    }

  
    async restoreListing(req, res) {
        try {
            const listingId = req.params.id;
            await this.listingService.restoreListing(listingId);
            
            res.json({ success: true, message: 'Listing restored successfully' });
        } catch (error) {
            console.error('Error restoring listing:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to restore listing' 
            });
        }
    }
}

module.exports =  ListingController;