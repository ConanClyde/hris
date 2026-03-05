import AIChatbotController from './AIChatbotController'
import AIChatbotDataController from './AIChatbotDataController'
import AIChatbotConversationController from './AIChatbotConversationController'
const Controllers = {
    AIChatbotController: Object.assign(AIChatbotController, AIChatbotController),
AIChatbotDataController: Object.assign(AIChatbotDataController, AIChatbotDataController),
AIChatbotConversationController: Object.assign(AIChatbotConversationController, AIChatbotConversationController),
}

export default Controllers