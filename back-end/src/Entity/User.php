<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\UserRoles;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['user']])]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups('user')]
    private ?string $email = null;

    #[ORM\Column]
    #[UserRoles]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('user')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('user')]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserNode::class, orphanRemoval: true)]
    private Collection $userNodes;

    #[ORM\Column]
    #[Groups('user')]
    private ?int $experience = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups('user')]
    private ?Rank $rank = null;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserAchievement::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userAchievements;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserQuest::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userQuests;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Story::class)]
    private Collection $stories;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserStory::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userStories;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Feedback::class)]
    private Collection $feedback;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Place::class)]
    private Collection $places;

    #[ORM\OneToMany(mappedBy: 'user1', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendships;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Epic::class)]
    private Collection $epics;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserEpic::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userEpics;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $invitations;

    #[ORM\Column]
    #[Groups('user')]
    private ?bool $isVerified = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Subscription::class, orphanRemoval: true)]
    private Collection $subscriptions;

    #[ORM\Column]
    private ?int $nbPrivateArticlesLeft = null;

    #[ORM\Column]
    private ?int $nbSponsoredArticlesLeft = null;

    #[ORM\Column]
    private ?int $nbPrivateAchievementsLeft = null;

    #[ORM\Column]
    private ?int $nbSponsoredAchievementsLeft = null;

    #[ORM\Column]
    private ?int $nbPrivateStoriesLeft = null;

    #[ORM\Column]
    private ?int $nbSponsoredStoriesLeft = null;

    #[ORM\Column]
    private ?int $nbPrivateQuestsLeft = null;

    #[ORM\Column]
    private ?int $nbSponsoredQuestsLeft = null;

    #[ORM\Column]
    private ?int $nbPrivateEpicsLeft = null;

    #[ORM\Column]
    private ?int $nbSponsoredEpicsLeft = null;

    #[ORM\Column]
    private ?int $nbPrivatePlacesLeft = null;

    #[ORM\Column]
    private ?int $nbSponsoredPlacesLeft = null;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserContinent::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userContinents;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserCountry::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userCountries;

    #[ORM\OneToMany(
        mappedBy: 'user', 
        targetEntity: UserRegion::class, 
        orphanRemoval: true,
        cascade:['persist']
    )]
    private Collection $userRegions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('user')]
    private ?string $fcmToken = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->userNodes = new ArrayCollection();
        $this->userAchievements = new ArrayCollection();
        $this->userQuests = new ArrayCollection();
        $this->stories = new ArrayCollection();
        $this->userStories = new ArrayCollection();
        $this->feedback = new ArrayCollection();
        $this->places = new ArrayCollection();
        $this->friendships = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->epics = new ArrayCollection();
        $this->userEpics = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->userContinents = new ArrayCollection();
        $this->userCountries = new ArrayCollection();
        $this->userRegions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return !empty($this->password) ? $this->password : '';
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }


    #[ORM\PrePersist()]
    public function prePersist(){
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setUpdatedAt(new DateTime());
        $this->setRoles($this->getRoles());
    }

    #[ORM\PreUpdate()]
    public function preUpdate(){
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserNode>
     */
    public function getUserNodes(): Collection
    {
        return $this->userNodes;
    }

    public function addUserNode(UserNode $userNode): self
    {
        if (!$this->userNodes->contains($userNode)) {
            $this->userNodes->add($userNode);
            $userNode->setUser($this);
        }

        return $this;
    }

    public function removeUserNode(UserNode $userNode): self
    {
        if ($this->userNodes->removeElement($userNode)) {
            // set the owning side to null (unless already changed)
            if ($userNode->getUser() === $this) {
                $userNode->setUser(null);
            }
        }

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getRank(): ?Rank
    {
        return $this->rank;
    }

    public function setRank(?Rank $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return Collection<int, UserAchievement>
     */
    public function getUserAchievements(?Collection $achievements = null): Collection
    {
        $userAchievements = $this->userAchievements;
        if(!empty($achievements)){
            $userAchievements->filter(function(UserAchievement $userAchievement) use($achievements) {
                return $achievements->contains($userAchievement->getAchievement());
            });
        }
        return $userAchievements;
    }


    public function isNewUserAchievement(UserAchievement $userAchievement): bool
    {
        foreach($this->getUserAchievements() as $alreadyUserAchievement){
            if($alreadyUserAchievement->getAchievement()->getId() == $userAchievement->getAchievement()->getId()){
                return false;
            }
        }
        return true;
    }


    public function addUserAchievement(UserAchievement $userAchievement): self
    {

        if ($this->isNewUserAchievement($userAchievement) && !$this->userAchievements->contains($userAchievement)) {
            $this->userAchievements->add($userAchievement);
            $userAchievement->setUser($this);
        }

        return $this;
    }

    public function removeUserAchievement(UserAchievement $userAchievement): self
    {
        if ($this->userAchievements->removeElement($userAchievement)) {
            // set the owning side to null (unless already changed)
            if ($userAchievement->getUser() === $this) {
                $userAchievement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserQuest>
     */
    public function getUserQuests(?Collection $quests = null): Collection
    {
        $userQuests = $this->userQuests;
        if(!empty($quests)){
            $userQuests->filter(function(UserQuest $userQuest) use($quests) {
                return $quests->contains($userQuest->getQuest());
            });
        }
        return $userQuests;

    }

    public function isNewUserQuest(UserQuest $userQuest): bool
    {
        foreach($this->getUserQuests() as $alreadyUserQuest){
            if($alreadyUserQuest->getQuest()->getId() == $userQuest->getQuest()->getId()){
                return false;
            }
        }
        return true;
    }

    public function addUserQuest(UserQuest $userQuest): self
    {
        if ($this->isNewUserQuest($userQuest) && !$this->userQuests->contains($userQuest)) {
            $this->userQuests->add($userQuest);
            $userQuest->setUser($this);
        }

        return $this;
    }

    public function removeUserQuest(UserQuest $userQuest): self
    {
        if ($this->userQuests->removeElement($userQuest)) {
            // set the owning side to null (unless already changed)
            if ($userQuest->getUser() === $this) {
                $userQuest->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Story>
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): self
    {
        if (!$this->stories->contains($story)) {
            $this->stories->add($story);
            $story->setAuthor($this);
        }

        return $this;
    }

    public function removeStory(Story $story): self
    {
        if ($this->stories->removeElement($story)) {
            // set the owning side to null (unless already changed)
            if ($story->getAuthor() === $this) {
                $story->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserStory>
     */
    public function getUserStories(?Collection $stories = null): Collection
    {

        $userStories = $this->userStories;
        if(!empty($stories)){
            $userStories->filter(function(UserStory $userStory) use($stories) {
                return $stories->contains($userStory->getStory());
            });
        }
        return $userStories;
    }

    public function isNewUserStory(UserStory $userStory): bool
    {
        foreach($this->getUserStories() as $alreadyUserStory){
            if($alreadyUserStory->getStory()->getId() == $userStory->getStory()->getId()){
                return false;
            }
        }
        return true;
    }

    public function addUserStory(UserStory $userStory): self
    {
        if ($this->isNewUserStory($userStory) && !$this->userStories->contains($userStory)) {
            $this->userStories->add($userStory);
            $userStory->setUser($this);
        }

        return $this;
    }

    public function removeUserStory(UserStory $userStory): self
    {
        if ($this->userStories->removeElement($userStory)) {
            // set the owning side to null (unless already changed)
            if ($userStory->getUser() === $this) {
                $userStory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setAuthor($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getAuthor() === $this) {
                $feedback->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
            $place->setAuthor($this);
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getAuthor() === $this) {
                $place->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friendship>
     */
    public function getFriendships(): Collection
    {
        return $this->friendships;
    }

    public function addFriendship(Friendship $friendship): self
    {
        if (!$this->friendships->contains($friendship)) {
            $this->friendships->add($friendship);
            $friendship->setUser1($this);
        }

        return $this;
    }

    public function removeFriendship(Friendship $friendship): self
    {
        if ($this->friendships->removeElement($friendship)) {
            // set the owning side to null (unless already changed)
            if ($friendship->getUser1() === $this) {
                $friendship->setUser1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Epic>
     */
    public function getEpics(): Collection
    {
        return $this->epics;
    }

    public function addEpic(Epic $epic): self
    {
        if (!$this->epics->contains($epic)) {
            $this->epics->add($epic);
            $epic->setAuthor($this);
        }

        return $this;
    }

    public function removeEpic(Epic $epic): self
    {
        if ($this->epics->removeElement($epic)) {
            // set the owning side to null (unless already changed)
            if ($epic->getAuthor() === $this) {
                $epic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserEpic>
     */
    public function getUserEpics(?Collection $epics = null): Collection
    {
        $userEpics = $this->userEpics;
        if(!empty($epics)){
            $userEpics->filter(function(UserEpic $userEpic) use($epics) {
                return $epics->contains($userEpic->getEpic());
            });
        }
        return $userEpics;
    }

    public function isNewUserEpic(UserEpic $userEpic): bool
    {
        foreach($this->getUserEpics() as $alreadyUserEpic){
            if($alreadyUserEpic->getEpic()->getId() == $userEpic->getEpic()->getId()){
                return false;
            }
        }
        return true;
    }

    public function addUserEpic(UserEpic $userEpic): self
    {
        if ($this->isNewUserEpic($userEpic) && !$this->userEpics->contains($userEpic)) {
            $this->userEpics->add($userEpic);
            $userEpic->setUser($this);
        }

        return $this;
    }

    public function removeUserEpic(UserEpic $userEpic): self
    {
        if ($this->userEpics->removeElement($userEpic)) {
            // set the owning side to null (unless already changed)
            if ($userEpic->getUser() === $this) {
                $userEpic->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setUser($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getUser() === $this) {
                $invitation->setUser(null);
            }
        }

        return $this;
    }

    #[Groups('user')]
    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }

    public function getNbPrivateArticlesLeft(): ?int
    {
        return $this->nbPrivateArticlesLeft;
    }

    public function setNbPrivateArticlesLeft(int $nbPrivateArticlesLeft): self
    {
        $this->nbPrivateArticlesLeft = $nbPrivateArticlesLeft;

        return $this;
    }

    public function getNbSponsoredArticlesLeft(): ?int
    {
        return $this->nbSponsoredArticlesLeft;
    }

    public function setNbSponsoredArticlesLeft(int $nbSponsoredArticlesLeft): self
    {
        $this->nbSponsoredArticlesLeft = $nbSponsoredArticlesLeft;

        return $this;
    }

    public function getNbPrivateAchievementsLeft(): ?int
    {
        return $this->nbPrivateAchievementsLeft;
    }

    public function setNbPrivateAchievementsLeft(int $nbPrivateAchievementsLeft): self
    {
        $this->nbPrivateAchievementsLeft = $nbPrivateAchievementsLeft;

        return $this;
    }

    public function getNbSponsoredAchievementsLeft(): ?int
    {
        return $this->nbSponsoredAchievementsLeft;
    }

    public function setNbSponsoredAchievementsLeft(int $nbSponsoredAchievementsLeft): self
    {
        $this->nbSponsoredAchievementsLeft = $nbSponsoredAchievementsLeft;

        return $this;
    }

    public function getNbPrivateStoriesLeft(): ?int
    {
        return $this->nbPrivateStoriesLeft;
    }

    public function setNbPrivateStoriesLeft(int $nbPrivateStoriesLeft): self
    {
        $this->nbPrivateStoriesLeft = $nbPrivateStoriesLeft;

        return $this;
    }

    public function getNbSponsoredStoriesLeft(): ?int
    {
        return $this->nbSponsoredStoriesLeft;
    }

    public function setNbSponsoredStoriesLeft(int $nbSponsoredStoriesLeft): self
    {
        $this->nbSponsoredStoriesLeft = $nbSponsoredStoriesLeft;

        return $this;
    }

    public function getNbPrivateQuestsLeft(): ?int
    {
        return $this->nbPrivateQuestsLeft;
    }

    public function setNbPrivateQuestsLeft(int $nbPrivateQuestsLeft): self
    {
        $this->nbPrivateQuestsLeft = $nbPrivateQuestsLeft;

        return $this;
    }

    public function getNbSponsoredQuestsLeft(): ?int
    {
        return $this->nbSponsoredQuestsLeft;
    }

    public function setNbSponsoredQuestsLeft(int $nbSponsoredQuestsLeft): self
    {
        $this->nbSponsoredQuestsLeft = $nbSponsoredQuestsLeft;

        return $this;
    }

    public function getNbPrivateEpicsLeft(): ?int
    {
        return $this->nbPrivateEpicsLeft;
    }

    public function setNbPrivateEpicsLeft(int $nbPrivateEpicsLeft): self
    {
        $this->nbPrivateEpicsLeft = $nbPrivateEpicsLeft;

        return $this;
    }

    public function getNbSponsoredEpicsLeft(): ?int
    {
        return $this->nbSponsoredEpicsLeft;
    }

    public function setNbSponsoredEpicsLeft(int $nbSponsoredEpicsLeft): self
    {
        $this->nbSponsoredEpicsLeft = $nbSponsoredEpicsLeft;

        return $this;
    }

    public function getNbPrivatePlacesLeft(): ?int
    {
        return $this->nbPrivatePlacesLeft;
    }

    public function setNbPrivatePlacesLeft(int $nbPrivatePlacesLeft): self
    {
        $this->nbPrivatePlacesLeft = $nbPrivatePlacesLeft;

        return $this;
    }

    public function getNbSponsoredPlacesLeft(): ?int
    {
        return $this->nbSponsoredPlacesLeft;
    }

    public function setNbSponsoredPlacesLeft(int $nbSponsoredPlacesLeft): self
    {
        $this->nbSponsoredPlacesLeft = $nbSponsoredPlacesLeft;

        return $this;
    }

    /**
     * @return Collection<int, UserContinent>
     */
    public function getUserContinents(): Collection
    {
        return $this->userContinents;
    }

    public function isNewUserContinent(UserContinent $userContinent): bool
    {
        foreach($this->getUserContinents() as $alreadyUserContinent){
            if($alreadyUserContinent->getContinent()->getId() == $userContinent->getContinent()->getId()){
                return false;
            }
        }
        return true;
    }

    public function addUserContinent(UserContinent $userContinent): self
    {
        if ($this->isNewUserContinent($userContinent) && !$this->userContinents->contains($userContinent)) {
            $this->userContinents->add($userContinent);
            $userContinent->setUser($this);
        }

        return $this;
    }

    public function removeUserContinent(UserContinent $userContinent): self
    {
        if ($this->userContinents->removeElement($userContinent)) {
            // set the owning side to null (unless already changed)
            if ($userContinent->getUser() === $this) {
                $userContinent->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserCountry>
     */
    public function getUserCountries(): Collection
    {
        return $this->userCountries;
    }

    public function isNewUserCountry(UserCountry $userCountry): bool
    {
        foreach($this->getUserCountries() as $alreadyUserCountry){
            if($alreadyUserCountry->getCountry()->getId() == $userCountry->getCountry()->getId()){
                return false;
            }
        }
        return true;
    }

    public function addUserCountry(UserCountry $userCountry): self
    {
        if ($this->isNewUserCountry($userCountry) && !$this->userCountries->contains($userCountry)) {
            $this->userCountries->add($userCountry);
            $userCountry->setUser($this);
        }

        return $this;
    }

    public function removeUserCountry(UserCountry $userCountry): self
    {
        if ($this->userCountries->removeElement($userCountry)) {
            // set the owning side to null (unless already changed)
            if ($userCountry->getUser() === $this) {
                $userCountry->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserRegion>
     */
    public function getUserRegions(): Collection
    {
        return $this->userRegions;
    }

    public function isNewUserRegion(UserRegion $userRegion): bool
    {
        foreach($this->getUserRegions() as $alreadyUserRegion){
            if($alreadyUserRegion->getRegion()->getId() == $userRegion->getRegion()->getId()){
                return false;
            }
        }
        return true;
    }

    public function addUserRegion(UserRegion $userRegion): self
    {
        if ($this->isNewUserRegion($userRegion) && !$this->userRegions->contains($userRegion)) {
            $this->userRegions->add($userRegion);
            $userRegion->setUser($this);
        }

        return $this;
    }

    public function removeUserRegion(UserRegion $userRegion): self
    {
        if ($this->userRegions->removeElement($userRegion)) {
            // set the owning side to null (unless already changed)
            if ($userRegion->getUser() === $this) {
                $userRegion->setUser(null);
            }
        }

        return $this;
    }

    public function getFcmToken(): ?string
    {
        return $this->fcmToken;
    }

    public function setFcmToken(?string $fcmToken): self
    {
        $this->fcmToken = $fcmToken;

        return $this;
    }
}
