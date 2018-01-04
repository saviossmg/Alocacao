<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Recursossalas
 *
 * @ORM\Table(name="recursossalas", indexes={@ORM\Index(name="fk_registros_has_sala_sala1_idx", columns={"Sala"}), @ORM\Index(name="fk_registros_has_sala_registros1_idx", columns={"Registro"})})
 * @ORM\Entity
 */
class Recursossalas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="Quantidade", type="integer", nullable=false)
     */
    private $quantidade;

    /**
     * @var \Registro
     *
     * @ORM\ManyToOne(targetEntity="Registro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Registro", referencedColumnName="Id")
     * })
     */
    private $registro;

    /**
     * @var \Sala
     *
     * @ORM\ManyToOne(targetEntity="Sala")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Sala", referencedColumnName="Id")
     * })
     */
    private $sala;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return Recursossalas
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set registro
     *
     * @param \Registro $registro
     *
     * @return Recursossalas
     */
    public function setRegistro(\Registro $registro = null)
    {
        $this->registro = $registro;

        return $this;
    }

    /**
     * Get registro
     *
     * @return \Registro
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Set sala
     *
     * @param \Sala $sala
     *
     * @return Recursossalas
     */
    public function setSala(\Sala $sala = null)
    {
        $this->sala = $sala;

        return $this;
    }

    /**
     * Get sala
     *
     * @return \Sala
     */
    public function getSala()
    {
        return $this->sala;
    }
}

