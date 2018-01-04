<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Turmaprofessor
 *
 * @ORM\Table(name="turmaprofessor", indexes={@ORM\Index(name="fk_alocacaoSalas_has_vwServidores_vwServidores1_idx", columns={"Professor"}), @ORM\Index(name="fk_alocacaoSalas_has_vwServidores_alocacaoSalas1_idx", columns={"AlocacaoSala"}), @ORM\Index(name="fk_TurmaProfessor_SemestreLetivo1_idx", columns={"SemestreLetivo"})})
 * @ORM\Entity
 */
class Turmaprofessor
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
     * @var \Semestreletivo
     *
     * @ORM\ManyToOne(targetEntity="Semestreletivo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SemestreLetivo", referencedColumnName="Id")
     * })
     */
    private $semestreletivo;

    /**
     * @var \Alocacaosala
     *
     * @ORM\ManyToOne(targetEntity="Alocacaosala")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="AlocacaoSala", referencedColumnName="Id")
     * })
     */
    private $alocacaosala;

    /**
     * @var \Vwservidor
     *
     * @ORM\ManyToOne(targetEntity="Vwservidor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Professor", referencedColumnName="Id")
     * })
     */
    private $professor;


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
     * Set semestreletivo
     *
     * @param \Semestreletivo $semestreletivo
     *
     * @return Turmaprofessor
     */
    public function setSemestreletivo(\Semestreletivo $semestreletivo = null)
    {
        $this->semestreletivo = $semestreletivo;

        return $this;
    }

    /**
     * Get semestreletivo
     *
     * @return \Semestreletivo
     */
    public function getSemestreletivo()
    {
        return $this->semestreletivo;
    }

    /**
     * Set alocacaosala
     *
     * @param \Alocacaosala $alocacaosala
     *
     * @return Turmaprofessor
     */
    public function setAlocacaosala(\Alocacaosala $alocacaosala = null)
    {
        $this->alocacaosala = $alocacaosala;

        return $this;
    }

    /**
     * Get alocacaosala
     *
     * @return \Alocacaosala
     */
    public function getAlocacaosala()
    {
        return $this->alocacaosala;
    }

    /**
     * Set professor
     *
     * @param \Vwservidor $professor
     *
     * @return Turmaprofessor
     */
    public function setProfessor(\Vwservidor $professor = null)
    {
        $this->professor = $professor;

        return $this;
    }

    /**
     * Get professor
     *
     * @return \Vwservidor
     */
    public function getProfessor()
    {
        return $this->professor;
    }
}

