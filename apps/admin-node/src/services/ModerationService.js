class ModerationService {
    
    async getPendingReports() {
        
        console.warn('ModerationService.getPendingReports is not implemented');
        return [];
    }
    
    async resolveReport(reportId, action) {
      
        console.warn('ModerationService.resolveReport is not implemented');
        return true;
    }
    
    async getReportDetails(reportId) {
     
        console.warn('ModerationService.getReportDetails is not implemented');
        return {};
    }
}

module.exports = new ModerationService();