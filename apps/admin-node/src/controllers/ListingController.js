class ListingController {
    constructor(listingService) {
        this.listingService = listingService;
    }




    /**
     * [VIEW] Renders Listing Management
     * GET /admin/listings
     */
    async getListingsView(req, res) {
        res.render('listings', {
            title: 'Marketplace Listings | SLU Bazaar Admin',
            path: '/listings'
        });
    }




    /**
     * [API] Get filtered listings
     * GET /admin/api/listings?q=calculus&status=Active
     */
    async getAllListings(req, res) {
        try {
            const filters = {
                search: req.query.q || '',
                status: req.query.status || 'all'
            };
            const items = await this.listingService.getAllListings(filters);
            res.json({ success: true, data: items });
        } catch (error) {
            res.status(500).json({ success: false, error: 'Failed to fetch listings' });
        }
    }




    /**
     * [API] Soft Delete Item
     * POST /admin/api/listings/:id/remove
     */
    async removeListing(req, res) {
        try {
            const itemId = req.params.id;
            await this.listingService.removeListing(itemId);
            res.json({ success: true, message: 'Item removed successfully' });
        } catch (error) {
            res.status(500).json({ success: false, error: error.message });
        }
    }




    /**
     * [API] Restore Item (Optional Utility)
     * POST /admin/api/listings/:id/restore
     */
    async restoreListing(req, res) {
        try {
            const itemId = req.params.id;
            await this.listingService.restoreListing(itemId);
            res.json({ success: true, message: 'Item restored to Active' });
        } catch (error) {
            res.status(500).json({ success: false, error: error.message });
        }
    }




    /**
     * [API] Sanitize Content (Req #10)
     * POST /admin/api/listings/:id/update
     */
    async updateListingContent(req, res) {
        try {
            const itemId = req.params.id;
            const { title, description } = req.body;

            if (!title || !description) {
                return res.status(400).json({ success: false, error: 'Title and Description required' });
            }

            await this.listingService.updateListingContent(itemId, title, description);
            res.json({ success: true, message: 'Listing updated successfully' });
        } catch (error) {
            res.status(500).json({ success: false, error: error.message });
        }
    }
}

module.exports = ListingController;