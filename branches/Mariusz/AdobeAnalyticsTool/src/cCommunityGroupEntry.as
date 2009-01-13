/**
 * 	cCommunityGroupEntry.as
 * 	Created by : Mariusz Choroszy
 * 	
 * 	This class represents one community entry.
 * 
 */

package
{
	import mx.collections.ArrayCollection;
	
	public class cCommunityGroupEntry
	{
		/**
		 * cCommunityGroupEntry
		 * 
		 */ 
		public function cCommunityGroupEntry(id:int, 
		 									 name:String, 
		 									 websites:ArrayCollection,
		 									 keywords:ArrayCollection)
		{
			mId = id;
			mName = name;
			mWebsites = websites;
			mKeywords = keywords;
		}
		
		/** The community database id **/
		public var mId:int;
		/** The community name **/
		public var mName:String;
		/** The collection of websites under this community **/
		public var mWebsites:ArrayCollection = new ArrayCollection();
		/** The collection of keywords for this community **/
		public var mKeywords:ArrayCollection = new ArrayCollection();
		
		/** Used to track if the entry was just created or not **/
		public var mJustCreated:Boolean = false;

	}
}